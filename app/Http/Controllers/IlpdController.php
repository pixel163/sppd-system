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
use App\Models\Laporan;
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
                    ->with('error', 'Akses ditolak: Form SPPD tidak ditemukan atau sudah diajukan/diproses.');
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
                ->with('error', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
        }

        // 4. Ambil Golongan User dari relasi SPPD -> User -> Golongan
        $golonganId = $sppd->user->golongan_id ?? null;

        // 5. Ambil Kategori Kota dari relasi SPPD -> Kota -> Kategori
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        // 6. Cari tarif yang cocok di tabel 'tarif'
        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

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

        // $durasi = (int) ($sppd->durasi_hari ?? 1);

        // // Parse input
        // $makanPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
        // $dinasPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
        // $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

        // // Total per komponen
        // $totalMakan = $makanPerHari * $durasi;
        // $totalDinas = $dinasPerHari * $durasi;
        // $totalHotel = $hotelPerMalam * $durasi;

        // // Grand Total langsung dijumlahkan (karena masing-masing sudah dikali durasi)
        // $grandTotal = $totalMakan + $totalDinas + $totalHotel;

        $durasi = (int) ($sppd->durasi ?? 1);

        // 4. Bersihkan/Parse Nominal Rupiah dari Input Form (Hapus Titik)
        $makanPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
        $dinasPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
        $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

        $totalMakan = $makanPerHari * $durasi;
        $totalDinas = $dinasPerHari * $durasi;
        $totalHotel = $hotelPerMalam * $durasi;

        // // 5. Kalkulasi Total Biaya
        // $durasiHari = (int) ($sppd->durasi ?? 1);
        $totalPerHari = $makanPerHari + $dinasPerHari + $hotelPerMalam;
        // $totalPerHari = $totalMakan + $totalDinas + $totalHotel;
        // $grandTotal = $totalPerHari * $durasiHari;
        $grandTotal = $totalPerHari * $durasi;

        // --- LOGIKA HITUNG SLA DINAMIS ---
        $dinas = Dinas::findOrFail($sppd->dinas_id);

        $slaDueAt =$dinas->overall_sla;
        // $tglBerangkat = Carbon::parse($request->tanggal_awal)->startOfDay();
        // $sekarang     = now();

        // if ($tglBerangkat->isPast()) {
        //     // Kasus: Pengajuan Susulan (Dinas sudah berlangsung/selesai)
        //     $slaDueAt = $sekarang->copy()->addHours(24);
        // } else {
        //     // Hitung sisa jam dari sekarang sampai tanggal keberangkatan
        //     $selisihJam = $sekarang->diffInHours($tglBerangkat, false);

        //     if ($selisihJam <= 24 && $selisihJam > 0) {
        //         // Mendadak (Berangkat < 24 jam lagi): SLA menyesuaikan sisa jam sebelum berangkat
        //         $slaDueAt = $tglBerangkat;
        //     } else {
        //         // Reguler (Berangkat masih lama): SLA standar 24 jam dari submit
        //         $slaDueAt = $sekarang->copy()->addHours(168);
        //     }
        // }

        // 6. Eksekusi Simpan dengan Database Transaction
        DB::transaction(function () use ($request, $sppd, $tarif, $makanPerHari, $dinasPerHari, $hotelPerMalam, $totalDinas, $totalHotel, $totalMakan, $grandTotal, $slaDueAt) {
        // DB::transaction(function () use ($request, $sppd, $tarif, $makanPerHari, $dinasPerHari, $hotelPerMalam, $grandTotal, $slaDueAt) {
            
            // A. Simpan ke Tabel `ilpd`
            $ilpd = Ilpd::create([
                'dinas_id'      => $sppd->dinas_id, // Mengambil dinas_id dari SPPD
                'sppd_id'       => $sppd->id,
                'no_ilpd'       => $this->generateNoIlpd(), // Panggil helper generator
                'tanggal_awal'  => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                // 'status'        => 'Sedang Diproses',
                'status'        => 'Menunggu Approval',
            ]);

            // B. Simpan ke Tabel `detail_ilpd`
            DetailIlpd::create([
                'ilpd_id'  => $ilpd->id,
                'tarif_id' => $tarif->id ?? null,
                // 'makan'    => $makanPerHari,
                // 'dinas'    => $dinasPerHari,
                // 'hotel'    => $hotelPerMalam,
                'makan' => $totalMakan,
                'dinas' => $totalDinas,
                'hotel' => $totalHotel,
                'laundry'  => 'Actual',
                'bbm'             => ($request->bbm),
                'transport_lokal' => ($request->transport_lokal),
                'visa'            => ($request->visa),
                'fiskal'          => ($request->fiskal),
                'airport_tax'     => ($request->airport_tax),
                'parkir_toll'     => ($request->parkir_toll), // Sesuaikan nama kolom migration
                'entertaiment'    => ($request->entertaiment),
                'dll'             => ($request->dll),
                'total'        => $grandTotal,
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
                // 'status'      => 'Sedang Diproses',
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

        $sppd = $ilpd->sppd;

        // 1. Ambil ID Kategori Kota dari kota tujuan di SPPD
        $kotaKategoriId = $ilpd->sppd->kota->kota_kategori_id ?? null;

        // 2. Ambil Golongan ID Pemohon (dari relasi user via SPPD)
        $golonganId = $ilpd->sppd->user->golongan_id ?? null;

        $transports = Transport::all(); 
        $keperluans = Keperluan::all();

        // 3. Ambil SEMUA data tarif yang Kategori Kotanya cocok dengan kota tujuan SPPD
        $tarifs = Tarif::with('golongan')
            ->where('kota_kategori_id', $kotaKategoriId)
            // ->where('golongan_id', $golonganId)
            ->get();

        // return view('ilpd.approve', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarifs'));
        return view('ilpd.approve', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarifs', 'golonganId'));
        // return view('approval.general', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarifs', 'golonganId'));
    }

    /**
     * Memproses persetujuan/pemeriksaan dari GA
     */
    public function approve(Request $request, $id)
    {
        // 1. Cek TTD User
        $user = Auth::user();
        if (!$user->signature) {
            return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
        }

        // 2. Validasi Input
        $request->validate([
            'tanggal_awal'      => 'required|date',
            'transport_id'      => 'nullable|array', // Karena JSON/Multi-select
            'keperluan_id'      => 'nullable|array', // Karena JSON/Multi-select
            'transport_lainnya' => 'nullable|string',
            'keperluan_lainnya' => 'nullable|string',
            'tiket_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bbm'               => 'nullable|numeric',
            'transport_lokal'   => 'nullable|numeric',
            'visa'              => 'nullable|numeric',
            'fiskal'            => 'nullable|numeric',
            'airport_tax'       => 'nullable|numeric',
            'parkir_toll'       => 'nullable|numeric', // Sesuaikan name di form HTML (parkir_toll)
            'entertaiment'      => 'nullable|numeric',
            'dll'               => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            // 3. Ambil data ILPD beserta SPPD-nya
            $ilpd = Ilpd::with('sppd')->findOrFail($id);
            $sppd = $ilpd->sppd;

            // --- A. HITUNG TANGGAL AKHIR BERDASARKAN DURASI SPPD ---
            $tanggalAwalBaru = \Carbon\Carbon::parse($request->tanggal_awal);
            $durasiHari = (int) ($sppd->durasi ?? 1);
            
            // Hitung tanggal akhir (misal: durasi 3 hari, tgl 1 s/d tgl 3 -> subDay(1) atau addDays sesuai rumus durasi sistemmu)
            $tanggalAkhirBaru = $tanggalAwalBaru->copy()->addDays($durasiHari - 1);

            // Update Tanggal ke tabel ILPD
            $ilpd->update([
                'tanggal_awal'  => $tanggalAwalBaru->format('Y-m-d'),
                'tanggal_akhir' => $tanggalAkhirBaru->format('Y-m-d'),
                'status'        => 'Disetujui',
            ]);

            // --- B. UPDATE TRANSPORT & KEPERLUAN KE TABEL SPPDS ---
            if ($sppd) {
                $sppd->update([
                    // Jika di Model Sppd $casts = ['transport_id' => 'array'], Laravel otomatis handles json_encode
                    // 'transport_id'      => $request->transport_id ? json_encode($request->transport_id) : null,
                    // 'keperluan_id'      => $request->keperluan_id ? json_encode($request->keperluan_id) : null,
                    'transport_id' => $request->transport_id,
                    'keperluan_id' => $request->keperluan_id,
                    'transport_lainnya' => $request->transport_lainnya,
                    'keperluan_lainnya' => $request->keperluan_lainnya,
                ]);
            }

            // --- C. UPLOAD TIKET (OPSIONAL) ---
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

            // --- D. UPDATE STATUS DINAS (JIKA ADA) ---
            if ($ilpd->dinas_id) {
                $dinas = Dinas::find($ilpd->dinas_id);
                
                if ($dinas) {
                    // Cek apakah waktu approve GA saat ini melebihi overall_sla master
                    $statusSlaFinal = now()->lessThanOrEqualTo($dinas->overall_sla) ? 'ON_TIME' : 'LATE';

                    $dinas->update([
                        'status'     => 'Disetujui',
                        'status_sla' => $statusSlaFinal, // Menandai finalisasi SLA (ON_TIME / LATE)
                    ]);
                }
            }
            // if ($ilpd->dinas_id) {
            //     Dinas::where('id', $ilpd->dinas_id)->update([
            //         'status' => 'Disetujui',
            //     ]);
            // }

            // --- E. UPDATE DETAIL ILPD (RINCIAN BIAYA) ---
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
                'parkir&toll'     => $request->parkir_toll, // name atribut di DB 'parkir&toll'
                'entertaiment'    => $request->entertaiment,
                'dll'             => $request->dll,
            ], function ($value) {
                return !is_null($value);
            });

            if (!empty($dataToUpdate)) {
                DetailIlpd::updateOrCreate(
                    ['ilpd_id' => $ilpd->id],
                    $dataToUpdate
                );
            }

            // --- F. LOG APPROVAL & SLA ---
            // $pendingApproval = IlpdApproval::where('ilpd_id', $ilpd->id)
            //     ->whereNull('approved_at')
            //     ->latest()
            //     ->first();

            // if ($pendingApproval) {
            //     $pendingApproval->update([
            //         'approver_id' => $user->id,
            //         'status'      => 'Disetujui',
            //         'signature'   => $user->signature,
            //         'approved_at' => now(),
            //     ]);
            // } else {
            $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => $user->id,
                'status'      => 'Disetujui',
                'signature'   => $user->signature,
                // 'sla_due_at'  => $lastApproval?->sla_due_at,
                'sla_due_at'  => $lastApproval?->sla_due_at ?? $ilpd->dinas?->overall_sla,
                'approved_at' => now(),
            ]);
            // }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan data diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
        }
    }
    // public function laporan($id)
    // {
    //     // Mengambil data ILPD beserta SPPD (+relasi kota & user), Detail ILPD, dan Tiket jika ada
    //     $ilpd = Ilpd::with([
    //         'dinas',
    //         'sppd.kota',
    //         'sppd.user.golongan', // tambahkan .golongan agar nama/data golongan user terbawa
    //         'detail_ilpd', // Relasi ke tabel detail_ilpd
    //     ])
    //     ->where('status', 'Sedang Diproses')
    //     ->findOrFail($id);

    //     $sppd = $ilpd->sppd;

    //     // 1. Ambil ID Kategori Kota dari kota tujuan di SPPD
    //     $kotaKategoriId = $ilpd->sppd->kota->kota_kategori_id ?? null;

    //     // 2. Ambil Golongan ID Pemohon (dari relasi user via SPPD)
    //     $golonganId = $ilpd->sppd->user->golongan_id ?? null;

    //     $transports = Transport::all(); 
    //     $keperluans = Keperluan::all();

    //     // 3. Ambil SEMUA data tarif yang Kategori Kotanya cocok dengan kota tujuan SPPD
    //     $tarifs = Tarif::with('golongan')
    //         ->where('kota_kategori_id', $kotaKategoriId)
    //         ->get();

    //     return view('ilpd.laporan', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarifs', 'golonganId'));
    // }

    // public function report(Request $request, $id)
    // {
    //     // 1. Cek TTD User
    //     $user = Auth::user();
    //     if (!$user->signature) {
    //         return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
    //     }

    //     // 2. Validasi Input
    //     $request->validate([
    //         'tanggal_awal'      => 'required|date',
    //         'transport_id'      => 'nullable|array', // Karena JSON/Multi-select
    //         'keperluan_id'      => 'nullable|array', // Karena JSON/Multi-select
    //         'transport_lainnya' => 'nullable|string',
    //         'keperluan_lainnya' => 'nullable|string',
    //         'tiket_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //         'bbm'               => 'nullable|numeric',
    //         'transport_lokal'   => 'nullable|numeric',
    //         'visa'              => 'nullable|numeric',
    //         'fiskal'            => 'nullable|numeric',
    //         'airport_tax'       => 'nullable|numeric',
    //         'parkir_toll'       => 'nullable|numeric', // Sesuaikan name di form HTML (parkir_toll)
    //         'entertaiment'      => 'nullable|numeric',
    //         'dll'               => 'nullable|numeric',
    //     ]);

    //     DB::beginTransaction();
    //     $filePath = null;

    //     try {
    //         // 3. Ambil data ILPD beserta SPPD-nya
    //         $ilpd = Ilpd::with('sppd')->findOrFail($id);
    //         $sppd = $ilpd->sppd;

    //         // --- A. HITUNG TANGGAL AKHIR BERDASARKAN DURASI SPPD ---
    //         $tanggalAwalBaru = \Carbon\Carbon::parse($request->tanggal_awal);
    //         $durasiHari = (int) ($sppd->durasi ?? 1);
            
    //         // Hitung tanggal akhir (misal: durasi 3 hari, tgl 1 s/d tgl 3 -> subDay(1) atau addDays sesuai rumus durasi sistemmu)
    //         $tanggalAkhirBaru = $tanggalAwalBaru->copy()->addDays($durasiHari - 1);

    //         // Update Tanggal ke tabel ILPDS
    //         $ilpd->update([
    //             'tanggal_awal'  => $tanggalAwalBaru->format('Y-m-d'),
    //             'tanggal_akhir' => $tanggalAkhirBaru->format('Y-m-d'),
    //             'status'        => 'Disetujui',
    //         ]);

    //         // --- B. UPDATE TRANSPORT & KEPERLUAN KE TABEL SPPDS ---
    //         if ($sppd) {
    //             $sppd->update([
    //                 // Jika di Model Sppd $casts = ['transport_id' => 'array'], Laravel otomatis handles json_encode
    //                 // 'transport_id'      => $request->transport_id ? json_encode($request->transport_id) : null,
    //                 // 'keperluan_id'      => $request->keperluan_id ? json_encode($request->keperluan_id) : null,
    //                 'transport_id' => $request->transport_id,
    //                 'keperluan_id' => $request->keperluan_id,
    //                 'transport_lainnya' => $request->transport_lainnya,
    //                 'keperluan_lainnya' => $request->keperluan_lainnya,
    //             ]);
    //         }

    //         // --- C. UPLOAD TIKET (OPSIONAL) ---
    //         if ($request->hasFile('tiket_file')) {
    //             $file = $request->file('tiket_file');
    //             $filePath = $file->store('tikets', 'public');

    //             Tiket::create([
    //                 'ilpd_id'     => $ilpd->id,
    //                 'type'        => 'jalan',
    //                 'file'        => $filePath,
    //                 'uploaded_by' => $user->id,
    //                 'uploaded_at' => now(),
    //             ]);
    //         }

    //         // --- D. UPDATE STATUS DINAS (JIKA ADA) ---
    //         if ($ilpd->dinas_id) {
    //             Dinas::where('id', $ilpd->dinas_id)->update([
    //                 'status' => 'Disetujui',
    //             ]);
    //         }

    //         // --- E. UPDATE DETAIL ILPD (RINCIAN BIAYA) ---
    //         $dataToUpdate = array_filter([
    //             'tarif_id'        => $request->tarif_id,
    //             'dinas'           => $request->dinas,
    //             'makan'           => $request->makan,
    //             'hotel'           => $request->hotel,
    //             'bbm'             => $request->bbm,
    //             'transport_lokal' => $request->transport_lokal,
    //             'visa'            => $request->visa,
    //             'fiskal'          => $request->fiskal,
    //             'airport_tax'     => $request->airport_tax,
    //             'parkir&toll'     => $request->parkir_toll, // name atribut di DB 'parkir&toll'
    //             'entertaiment'    => $request->entertaiment,
    //             'dll'             => $request->dll,
    //         ], function ($value) {
    //             return !is_null($value);
    //         });

    //         if (!empty($dataToUpdate)) {
    //             DetailIlpd::updateOrCreate(
    //                 ['ilpd_id' => $ilpd->id],
    //                 $dataToUpdate
    //             );
    //         }

    //         // --- F. LOG APPROVAL & SLA ---
    //         $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

    //         IlpdApproval::create([
    //             'ilpd_id'     => $ilpd->id,
    //             'approver_id' => $user->id,
    //             'status'      => 'Disetujui',
    //             'signature'   => $user->signature,
    //             'sla_due_at'  => $lastApproval?->sla_due_at,
    //             'approved_at' => now(),
    //         ]);

    //         DB::commit();

    //         return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan data diperbarui.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         if ($filePath && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
    //     }
    // }
    // public function laporan($ilpd_id)
    public function laporan($id)
    {
        // Fetch ILPD lengkap dengan SPPD, Kota, & Detail Biaya Pengajuan
        $ilpd = Ilpd::with(['sppd.kota', 'detail_ilpd', 'laporan', 'sppd.user'])
                    ->findOrFail($id);

        // Ambil data laporan jika sudah pernah diisi (untuk mode edit/view)
        $laporan = $ilpd->laporan ?? new Laporan();

        return view('ilpd.laporan', compact('ilpd', 'laporan'));
    }

    /**
     * Menyimpan atau Memperbarui Laporan Perjalanan Dinas (LPJ)
     */
    public function report(Request $request, $id)
    {
        $ilpd = Ilpd::with('detail_ilpd')->findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'realisasi'       => 'required|array',
            'uang_muka'       => 'nullable|numeric|min:0',
            'total_realisasi' => 'required|numeric|min:0',
            'total_dibayar'   => 'required|numeric|min:0',
            'selisih'         => 'required|numeric',
            'keterangan'      => 'nullable|string',
            'laporan_1'       => 'nullable|string',
            'laporan_2'       => 'nullable|string',
            // 'status'                   => 'required|in:draft,submitted',
        ]);

        // Ambil total perkiraan dari detail_ilpd (auto sum)
        $totalPerkiraan = $ilpd->detail_ilpd ? $ilpd->detail_ilpd->sum('uang_muka') : 0;

        // Hitung ulang selisih di BE untuk keamanan data
        $uangMuka = (float) $request->input('uang_muka', 0);
        $totalRealisasi = (float) $request->input('total_realisasi', 0);
        
        // FIX BE: Uang Muka - Total Realisasi
        $selisihBE = $uangMuka - $totalRealisasi;

        DB::transaction(function () use ($request, $ilpd, $totalPerkiraan) {
        
            // 1. Simpan / Update data Laporan Dinas
            Laporan::updateOrCreate(
                ['ilpd_id' => $ilpd->id],
                [
                    'realisasi'       => $request->input('realisasi'),
                    'perkiraan'       => $totalPerkiraan,
                    'uang_muka'       => $request->input('uang_muka', 0),
                    'total_realisasi' => $request->input('total_realisasi', 0),
                    'total_dibayar'   => $request->input('total_dibayar', 0),
                    'selisih'         => $selisihBE,
                    // 'selisih'         => $request->input('selisih', 0),
                    'keterangan'      => $request->input('keterangan'),
                    'laporan_1'       => $request->input('laporan_1'),
                    'laporan_2'       => $request->input('laporan_2'),
                ]
            );

            // 2. Update status pada tabel dinas
            // Opsi A: Jika $ilpd memiliki relasi ke model Dinas ($ilpd->dinas)
            if ($ilpd->dinas) {
                $ilpd->dinas->update([
                    'status' => 'Selesai'
                ]);
            } 
            // Opsi B: Jika ilpd_id ada langsung di tabel dinas (Foreign Key)
            else {
                Dinas::where('ilpd_id', $ilpd->id)->update([
                    'status' => 'Selesai'
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Laporan Perjalanan Dinas berhasil disimpan.');
    }
        // Simpan / Update data Laporan Dinas
        // Laporan::updateOrCreate(
        //     ['ilpd_id' => $ilpd->id],
        //     [
        //         'realisasi'       => $request->input('realisasi'), // Array 12 komponen biaya
        //         'perkiraan'       => $totalPerkiraan,
        //         'uang_muka'       => $request->input('uang_muka', 0),
        //         'total_realisasi' => $request->input('total_realisasi', 0),
        //         'total_dibayar'   => $request->input('total_dibayar', 0),
        //         'selisih'         => $request->input('selisih', 0),
        //         'keterangan'      => $request->input('keterangan'),
        //         'laporan_1'       => $request->input('laporan_1'),
        //         'laporan_2'       => $request->input('laporan_2'),
        //         // 'status'                   => $request->input('status', 'submitted'),
        //     ]
        // );

        // return redirect()->route('dashboard')->with('success', 'Laporan Perjalanan Dinas berhasil disimpan.');
        // return redirect()->back()->with('success', 'Laporan Perjalanan Dinas berhasil disimpan.');
    // }
}