<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\Transport;
use App\Models\Keperluan;
use App\Models\Kota;
use App\Models\Tarif;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
use App\Models\DetailIlpd;
use App\Models\Tiket;
// use App\Models\Ticket;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IlpdController extends Controller
{
    public function create($sppdId = null)
    {
        $userId = auth()->id();

        // 1. Jika URL membawa ID spesifik (misal: /ilpd/create/15)
        if ($sppdId) {
            $sppd = Sppd::where('id', $sppdId)
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
            $sppd = Sppd::where('user_id', $userId)
                    ->where('status', 'Draft') // <-- BATASAN DI SINI
                    ->latest()
                    ->first();
        }

        // 3. Jika user SAMA SEKALI BELUM PERNAH buat Form 1 (SPPD)
        if (!$sppd) {
            return redirect()->route('sppd.create')
                ->with('warning', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
        }

        if ($sppd->status !== 'Draft') {
            return redirect()->route('dashboard')
                ->with('warning', 'Form 2 untuk SPPD ini sudah dikirim atau sudah diproses.');
        }

        // 4. Ambil Golongan User dari relasi SPPD -> User -> Golongan
        $golonganId = $sppd->user->golongan_id ?? null;

        // 5. Ambil Kategori Kota dari relasi SPPD -> Kota -> Kategori
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        // 6. Cari tarif yang cocok di tabel 'tarif'
        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        $transports = Transport::all(); 
        $keperluans = Keperluan::all(); 

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

        // 6. Eksekusi Simpan dengan Database Transaction
        DB::transaction(function () use ($request, $sppd, $tarif, $makanPerHari, $dinasPerHari, $hotelPerMalam, $grandTotal) {
            
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
                'bbm'      => null,
                'transport_lokal' => null,
                'visa'     => null,
                'fiskal'   => null,
                'airport_tax' => null,
                'parkir&toll' => null,
                'entertaiment' => null,
                'dll'      => null,
                'total'    => $grandTotal,
                'uang_muka'    => $grandTotal,
            ]);

            // C. Update Status pada Tabel `sppd`
            $sppd->update([
                'status' => 'Menunggu Approval'
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
            'sppd.user',
            'detailIlpd', // Relasi ke tabel detail_ilpd
            // 'tiket'       // Relasi ke tabel tiket
        ])
        // ->where('status', 'Menunggu Approval')
        ->where('status', 'Sedang Diproses')
        ->findOrFail($id);

        // $kotaKategoriId = $ilpd->sppd?->kota?->kategori_kota_id;
        // // Ambil semua pilihan tarif untuk kota tujuan tersebut
        // $tarifMasterList = Tarif::with('golongan')
        //     ->where('kota_kategori_id', $kotaKategoriId)
        //     ->get();

        $transports = Transport::all(); 
        $keperluans = Keperluan::all(); 

        return view('ilpd.approve', compact('ilpd', 'transports', 'keperluans'));
        // return view('ilpd.approve', compact('ilpd', 'transports', 'keperluans', 'tarifMasterList'));
    }

    /**
     * Memproses persetujuan/pemeriksaan dari GA
     */
    public function approve(Request $request, $id)
    {
        // 1. Cek dulu apakah user yang login sudah set TTD di profilnya
        $user = Auth::user();
        if (!$user->signature) {
            return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
        }

        // Validasi file tiket dan optional input lainnya
        $request->validate([
            'tiket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
            'bbm'        => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            // 2. Ambil data ILPD beserta relasinya
            $ilpd = Ilpd::findOrFail($id);

            // 3. Upload file tiket
            $filePath = null;
            if ($request->hasFile('tiket_file')) {
                $file = $request->file('tiket_file');
                $filePath = $file->store('tikets', 'public');

                Tiket::create([
                    'ilpd_id'     => $ilpd->id,
                    'type'        => 'jalan', // Sesuaikan nilainya
                    'file'        => $filePath,
                    'uploaded_by' => $user->id,
                    'uploaded_at' => now(),
                ]);
            }

            // 4. Update status di tabel 'ilpds'
            $ilpd->update([
                'status' => 'Disetujui',
            ]);

            // 5. Update status di tabel 'dinas' (jika ada relasinya)
            if ($ilpd->dinas_id) {
                Dinas::where('id', $ilpd->dinas_id)->update([
                    'status' => 'Disetujui',
                ]);
            }

            // 6. Handle perubahan / update di tabel 'detail_ilpds'
            if ($request->filled('bbm')) {
                DetailIlpd::updateOrCreate(
                    ['ilpd_id' => $ilpd->id],
                    ['bbm' => $request->bbm]
                );
            }

            // 7. Buat record di tabel 'ilpd_approvals' dengan TTD Gambar dari user
            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => $user->id,
                'status'      => 'Disetujui',
                'signature'   => $user->signature, // <--- Menggunakan path file TTD dari profil user
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan tiket telah diunggah.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file tiket yang terlanjur terunggah jika terjadi error database
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
        }
    }
    // public function approve(Request $request, $id)
    // // public function approve($id)
    // {
    //     // Validasi file tiket dan optional input lainnya
    //     $request->validate([
    //         'tiket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
    //         'bbm'        => 'nullable|numeric',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // 1. Ambil data ILPD beserta relasinya
    //         $ilpd = Ilpd::findOrFail($id);

    //         // 2. Upload file tiket
    //         if ($request->hasFile('tiket_file')) {
    //             $file = $request->file('tiket_file');
    //             $filePath = $file->store('tikets', 'public');

    //             // 3. Buat record baru di tabel 'tickets'
    //             Tiket::create([
    //                 'ilpd_id'     => $ilpd->id,
    //                 'type'        => 'jalan', // Sesuaikan nilainya (misal: 'ticket', 'flight', 'train', dll)
    //                 'file'        => $filePath,
    //                 'uploaded_by' => Auth::id(),
    //                 'uploaded_at' => now(),
    //             ]);
    //         }

    //         // 4. Update status di tabel 'ilpds'
    //         $ilpd->update([
    //             'status' => 'Disetujui', // Sesuaikan dengan enum/string status kamu
    //         ]);

    //         // 5. Update status di tabel 'dinas' (jika ada relasinya ke dinas)
    //         if ($ilpd->dinas_id) {
    //             Dinas::where('id', $ilpd->dinas_id)->update([
    //                 'status' => 'Disetujui', // Atau status relevan lainnya
    //             ]);
    //         }

    //         // 6. Handle perubahan / update di tabel 'detail_ilpds' (jika ada input seperti BBM/Nominal)
    //         if ($request->filled('bbm')) {
    //             DetailIlpd::updateOrCreate(
    //                 ['ilpd_id' => $ilpd->id],
    //                 [
    //                     'bbm' => $request->bbm,
    //                     // Tambahkan field lain jika ada perubahan detail
    //                 ]
    //             );
    //         }

    //         $digitalSignature = 'Approved electronically by ' . Auth::user()->name . ' on ' . now()->format('d M Y H:i:s');
    //         // 7. Buat data baru di tabel 'ilpd_approvals' sebagai audit log
    //         IlpdApproval::create([
    //             'ilpd_id'     => $ilpd->id,
    //             'approver_id' => Auth::id(),
    //             'status'      => 'Disetujui',
    //             // 'signature'   => $request->signature ?? null, // Diisi jika ada input tanda tangan/path ttd, atau set null
    //             'signature'   => $digitalSignature,
    //             'approved_at' => now(),
    //         ]);

    //         DB::commit();

    //         return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan tiket telah diunggah.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         // Hapus file yang terlanjur terunggah jika terjadi exception
    //         if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
    //     }
    // }
}