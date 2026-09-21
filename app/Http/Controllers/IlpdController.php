<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Transport;
use App\Models\Keperluan;
use App\Models\Golongan;
use App\Models\Kota;
use App\Models\Tarif;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
use App\Models\DetailIlpd;
use App\Models\Tiket;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IlpdController extends Controller
{
    public function create($sppdId = null)
    {
        $userId = auth()->id();

        // 1. Jika URL membawa ID spesifik (misal: /ilpd/create/15)
        if ($sppdId) {
            $sppd = Sppd::with(['user', 'kota'])
                    ->where('id', $sppdId)
                    ->where('user_id', $userId)
                    ->where('status', 'Draft') // LOCK STATUS DRAFT
                    ->first();

            // Jika ID ada tapi bukan milik user yang sedang login
            if (!$sppd) {
                return redirect()->route('dashboard')
                    ->with('warning', 'Akses ditolak: Form SPPD tidak ditemukan atau sudah diajukan/diproses.');
            }
        } else {
            // 2. Jika diakses dari Sidebar tanpa ID (/ilpd/create), ambil SPPD terbaru milik user
            $sppd = Sppd::with(['user', 'kota'])
                    ->where('user_id', $userId)
                    ->where('status', 'Draft') // LOCK STATUS DRAFT
                    ->first();
        }

        // 3. Jika user SAMA SEKALI BELUM PERNAH buat Form 1 (SPPD)
        if (!$sppd) {
            return redirect()->route('sppd.create')
                ->with('warning', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
        }

        // if ($sppd->status !== 'Draft') {
        //     return redirect()->route('dashboard')
        //         ->with('warning', 'Form 2 untuk SPPD ini sudah dikirim atau sudah diproses.');
        // }

        // 4. Ambil Golongan User dari relasi SPPD -> User -> Golongan
        $golonganId = $sppd->user->golongan_id ?? null;

        // 5. Ambil Kategori Kota dari relasi SPPD -> Kota -> Kategori
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        // 6. Cari tarif yang cocok di tabel 'tarif'
        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        // $transports = Transport::all(); 
        // $keperluans = Keperluan::all();

        $transports = Transport::where('is_active', true)->get(); 
        $keperluans = Keperluan::where('is_active', true)->get();

        return view('ilpd.create', compact('sppd', 'transports', 'keperluans', 'tarif'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'sppd_id'       => 'required|exists:sppd,id',
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // 2. Ambil Data SPPD Terkait
        $sppdId = $request->input('sppd_id');
        $sppd = Sppd::with(['user', 'kota'])->findOrFail($sppdId);

        // 3. Ambil Tarif Berdasarkan Golongan User & Kategori Kota
        $golonganId     = $sppd->user->golongan_id ?? null;
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        // 4. Bersihkan/Parse Nominal Rupiah dari Input Form (Hapus Titik)
        $makanPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
        $dinasPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
        $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

        // 5. Kalkulasi Total Biaya
        $durasiHari = (int) ($sppd->durasi ?? 1);
        $totalPerHari = $makanPerHari + $dinasPerHari + $hotelPerMalam;
        $grandTotal = $totalPerHari * $durasiHari;

        // --- LOGIKA HITUNG SLA DINAMIS ---
        $tglBerangkat = Carbon::parse($request->tanggal_awal)->startOfDay();
        $sekarang     = now();

        if ($tglBerangkat->isPast()) {
            // Kasus: Pengajuan Susulan (Dinas sudah berlangsung/selesai)
            $slaDueAt = $sekarang->copy()->addHours(24);
        } else {
            // Hitung sisa jam dari sekarang sampai tanggal keberangkatan
            $selisihJam = $sekarang->diffInHours($tglBerangkat, false);

            if ($selisihJam <= 24 && $selisihJam > 0) {
                // Mendadak (Berangkat < 24 jam lagi): SLA menyesuaikan sisa jam sebelum berangkat
                $slaDueAt = $tglBerangkat;
            } else {
                // Reguler (Berangkat masih lama): SLA standar 24 jam dari submit
                $slaDueAt = $sekarang->copy()->addHours(168);
            }
        }

        // 6. Eksekusi Simpan dengan Database Transaction
        DB::transaction(function () use ($request, $sppd, $tarif, $makanPerHari, $dinasPerHari, $hotelPerMalam, $grandTotal, $slaDueAt) {
            
            // A. Simpan ke Tabel `ilpd`
            $ilpd = Ilpd::create([
                'dinas_id'      => $sppd->dinas_id, // Mengambil dinas_id dari SPPD
                'sppd_id'       => $sppd->id,
                'no_ilpd'       => $this->generateNoIlpd(), // Panggil helper generator
                'tanggal_awal'  => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status'        => 'Menunggu Approval',
            ]);

            // B. Simpan ke Tabel `detail_ilpd`
            DetailIlpd::create([
                'ilpd_id'  => $ilpd->id,
                'tarif_id' => $tarif->id ?? null,
                'makan'    => $makanPerHari,
                'dinas'    => $dinasPerHari,
                'hotel'    => $hotelPerMalam,
                'laundry'    => 'Actual',
                'bbm'             => ($request->bbm),
                'transport_lokal' => ($request->transport_lokal),
                'visa'            => ($request->visa),
                'fiskal'          => ($request->fiskal),
                'airport_tax'     => ($request->airport_tax),
                'parkir_toll'     => ($request->parkir_toll), // Sesuaikan nama kolom migration
                'entertaiment'    => ($request->entertaiment),
                'dll'             => ($request->dll),
                // 'bbm'             => $request->filled('bbm') ? ($request->bbm) : null,
                // 'transport_lokal' => $request->filled('transport_lokal') ? ($request->transport_lokal) : null,
                // 'visa'            => $request->filled('visa') ? ($request->visa) : null,
                // 'fiskal'          => $request->filled('fiskal') ? ($request->fiskal) : null,
                // 'airport_tax'     => $request->filled('airport_tax') ? ($request->airport_tax) : null,
                // 'parkir&toll'     => $request->filled('parkir&toll') ? ($request->parkirtoll) : null,
                // 'entertaiment'    => $request->filled('entertaiment') ? ($request->entertaiment) : null,
                // 'dll'             => $request->filled('dll') ? ($request->dll) : null,
                'total'    => $grandTotal,
                'uang_muka'    => $grandTotal,
            ]);

            // C. Update Status pada Tabel `sppd`
            $sppd->update([
                'status' => 'Menunggu Approval'
            ]);

            // D. Simpan Log Approval Baru untuk `sppd_approval`
            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => null,
                'status'      => 'Menunggu Approval',
                'signature'   => null,
                'sla_due_at'  => $slaDueAt,
                'approved_at' => null,
            ]);

            // E. Simpan Log Approval Baru untuk `ilpd_approval`
            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => null,
                'status'      => 'Menunggu Approval',
                'signature'   => null,
                'sla_due_at'  => $slaDueAt,
                'approved_at' => null,
            ]);

            // D. Update Status pada Tabel `dinas` (jika dinas_id tersedia)
            if ($sppd->dinas_id) {
                Dinas::where('id', $sppd->dinas_id)->update([
                    'status' => 'Menunggu Approval'
                ]);
            }
        });

        return redirect('/dashboard')->with('success', 'ILPD berhasil diajukan!');
    }

    /**
     * Helper Function untuk Generate No ILPD Otomatis
     * Contoh Format: ILPD/2026/09/0001
     */
    private function generateNoIlpd()
    {
        $prefix = 'ILPD/' . date('Y/m') . '/';
        $lastIlpd = Ilpd::where('no_ilpd', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastIlpd) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastIlpd->no_ilpd, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        $ilpd = Ilpd::findOrFail($id);

        // Ambil data pendukung untuk dropdown pilihan
        $users      = User::select('id', 'name', 'nik')->get();
        $kotas      = Kota::all();
        $keperluans = Keperluan::all();
        $transports = Transport::all();

        return view('ilpd.edit', compact('ilpd', 'users', 'kotas', 'keperluans', 'transports'));
    }

    /**
     * Menampilkan halaman Blade Approve untuk GA
     */
    public function show($id)
    {
        // Mengambil data ILPD beserta SPPD (+relasi kota & user), Detail ILPD, dan Tiket jika ada
        $ilpd = Ilpd::with([
            'dinas',
            'sppd.kota',
            'sppd.user.golongan', // tambahkan .golongan agar nama/data golongan user terbawa
            'detail_ilpd', // Relasi ke tabel detail_ilpd
        ])
        ->where('status', 'Sedang Diproses')
        ->findOrFail($id);

        // 1. Ambil ID Kategori Kota dari kota tujuan di SPPD
        $kotaKategoriId = $ilpd->sppd->kota->kota_kategori_id ?? null;

        // 2. Ambil Golongan ID Pemohon (dari relasi user via SPPD)
        $golonganId = $ilpd->sppd->user->golongan_id ?? null;

        $transports = Transport::all(); 
        $keperluans = Keperluan::all();

        // 3. Ambil SEMUA data tarif yang Kategori Kotanya cocok dengan kota tujuan SPPD
        $tarifs = Tarif::with('golongan')
            ->where('kota_kategori_id', $kotaKategoriId)
            ->get();

        return view('ilpd.approve', compact('ilpd', 'transports', 'keperluans', 'tarifs', 'golonganId'));
    }
    // public function show($id)
    // {
    //     // Mengambil data ILPD beserta SPPD (+relasi kota & user), Detail ILPD, dan Tiket jika ada
    //     $ilpd = Ilpd::with([
    //         'dinas',
    //         'sppd.kota',
    //         'sppd.user',
    //         'detail_ilpd', // Relasi ke tabel detail_ilpd
    //     ])
    //     ->where('status', 'Sedang Diproses')
    //     ->findOrFail($id);

    //     $kotaKategoriId = $ilpd->sppd->kota->kota_kategori_id ?? null;
    //     $transports = Transport::all(); 
    //     $keperluans = Keperluan::all();
    //     // $golonganId   = Golongan::all();
    //     $golonganId     = $sppd->user->golongan_id ?? null;
    //     // $tarifs = Tarif::with('kota_kategori_id', $kotaKategoriId)
    //     //     ->get();
    //     $tarifs = Tarif::where('kota_kategori_id', $kotaKategoriId)
    //     // ->where($golongan)
    //     ->where('golongan_id', $golonganId)
    //     ->get();

    //     return view('ilpd.approve', compact('ilpd', 'transports', 'keperluans','tarifs'));
    // }

    /**
     * Memproses persetujuan/pemeriksaan dari GA
     */
    public function approve(Request $request, $id)
    {
        // 1. Cek apakah user yang login sudah set TTD di profilnya
        $user = Auth::user();
        if (!$user->signature) {
            return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
        }

        // 2. Validasi file tiket dan input opsional
        $request->validate([
            'tiket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
            'bbm'        => 'nullable|numeric',
            'transport_lokal' => 'nullable|numeric',
            'visa'       => 'nullable|numeric',
            'fiskal'     => 'nullable|numeric',
            'airport_tax' => 'nullable|numeric',
            'parkir&toll' => 'nullable|numeric',
            'entertaiment' => 'nullable|numeric',
            'dll'        => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        $filePath = null;

        try {
            // 3. Ambil data ILPD beserta relasinya
            $ilpd = Ilpd::findOrFail($id);

            // 4. Upload file tiket
            if ($request->hasFile('tiket_file')) {
                $file = $request->file('tiket_file');
                $filePath = $file->store('tikets', 'public');

                Tiket::create([
                    'ilpd_id'     => $ilpd->id,
                    'type'        => 'jalan',
                    'file'        => $filePath,
                    'uploaded_by' => $user->id,
                    'uploaded_at' => now(),
                ]);
            }

            // 5. Update status di tabel 'ilpds'
            $ilpd->update([
                'status' => 'Disetujui',
            ]);

            // 6. Update status di tabel 'dinas' (jika ada relasinya)
            if ($ilpd->dinas_id) {
                Dinas::where('id', $ilpd->dinas_id)->update([
                    'status' => 'Disetujui',
                ]);
            }

            // 7. Handle update di tabel 'detail_ilpds'
            $dataToUpdate = array_filter([
                'tarif_id'        => $request->tarif_id,
                'dinas'           => $request->dinas,
                'makan'           => $request->makan,
                'hotel'           => $request->hotel,
                'bbm'             => $request->bbm,
                'transport_lokal' => $request->transport_lokal,
                'visa'            => $request->visa,
                'fiskal'          => $request->fiskal,
                'airport_tax'     => $request->airport_tax,
                'parkir&toll'     => $request->parkir_toll,
                'entertaiment'    => $request->entertaiment,
                'dll'             => $request->dll,
            ], function ($value) {
                return !is_null($value); // Hanya simpan/update field yang diisi/memiliki nilai
            });

            // Jalankan updateOrCreate sekaligus jika ada data yang diisi
            if (!empty($dataToUpdate)) {
                DetailIlpd::updateOrCreate(
                    ['ilpd_id' => $ilpd->id],
                    $dataToUpdate
                );
            }
            // if ($request->filled('bbm')) {
            //     DetailIlpd::updateOrCreate(
            //         ['ilpd_id' => $ilpd->id],
            //         ['bbm' => $request->bbm]
            //     );
            // }

            // --- TAMBAHAN PENTING UNTUK SLA ---
            // Ambil deadline SLA dari record 'Menunggu Approval' sebelumnya
            $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

            // 8. Buat record riwayat baru di tabel 'ilpd_approvals'
            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => $user->id,
                'status'      => 'Disetujui',
                'signature'   => $user->signature,
                'sla_due_at'  => $lastApproval?->sla_due_at, // Forward batas waktu SLA dari tahap sebelumnya
                'approved_at' => now(),                     // Timer SLA Form 2 SELESAI di sini
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan tiket telah diunggah.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file tiket jika terjadi error pada database
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
        }
    }
    // public function approve(Request $request, $id)
    // {
    //     // 1. Cek dulu apakah user yang login sudah set TTD di profilnya
    //     $user = Auth::user();
    //     if (!$user->signature) {
    //         return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
    //     }

    //     // Validasi file tiket dan optional input lainnya
    //     $request->validate([
    //         'tiket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
    //         'bbm'        => 'nullable|numeric',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // 2. Ambil data ILPD beserta relasinya
    //         $ilpd = Ilpd::findOrFail($id);

    //         // 3. Upload file tiket
    //         $filePath = null;
    //         if ($request->hasFile('tiket_file')) {
    //             $file = $request->file('tiket_file');
    //             $filePath = $file->store('tikets', 'public');

    //             Tiket::create([
    //                 'ilpd_id'     => $ilpd->id,
    //                 'type'        => 'jalan', // Sesuaikan nilainya
    //                 'file'        => $filePath,
    //                 'uploaded_by' => $user->id,
    //                 'uploaded_at' => now(),
    //             ]);
    //         }

    //         // 4. Update status di tabel 'ilpds'
    //         $ilpd->update([
    //             'status' => 'Disetujui',
    //         ]);

    //         // 5. Update status di tabel 'dinas' (jika ada relasinya)
    //         if ($ilpd->dinas_id) {
    //             Dinas::where('id', $ilpd->dinas_id)->update([
    //                 'status' => 'Disetujui',
    //             ]);
    //         }

    //         // 6. Handle perubahan / update di tabel 'detail_ilpds'
    //         if ($request->filled('bbm')) {
    //             DetailIlpd::updateOrCreate(
    //                 ['ilpd_id' => $ilpd->id],
    //                 ['bbm' => $request->bbm]
    //             );
    //         }

    //         // 7. Buat record di tabel 'ilpd_approvals' dengan TTD Gambar dari user
    //         IlpdApproval::create([
    //             'ilpd_id'     => $ilpd->id,
    //             'approver_id' => $user->id,
    //             'status'      => 'Disetujui',
    //             'signature'   => $user->signature, // <--- Menggunakan path file TTD dari profil user
    //             'approved_at' => now(),
    //         ]);

    //         DB::commit();

    //         return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan tiket telah diunggah.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Hapus file tiket yang terlanjur terunggah jika terjadi error database
    //         if ($filePath && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
    //     }
    // }
}