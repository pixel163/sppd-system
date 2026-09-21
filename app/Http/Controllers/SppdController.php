<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\Ilpd;
use App\Models\SppdApproval;
use App\Models\IlpdApproval;
use App\Models\Dinas;
use App\Models\Kota;
use App\Models\Keperluan;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SppdController extends Controller
{
    /**
     * Menampilkan form SPPD
     */
    public function create()
    {
        $user = Auth::user();

        // Daftar status yang dianggap masih berjalan/aktif
        // $activeStatuses = ['Menunggu Approval', 'Sedang Diproses', 'Disetujui'];
        $activeStatuses = ['Draft','Menunggu Approval', 'Disetujui'];

        // Ambil HANYA 1 data pengajuan terbaru milik user
        $lastForm = Sppd::where('user_id', $user->id)
            ->latest() // Mengurutkan berdasarkan created_at terbaru
            ->first();

        // // Cek jika data terakhir ada DAN statusnya termasuk dalam activeStatuses
        if ($lastForm && in_array($lastForm->status, $activeStatuses)) {
            return redirect()->route('dashboard')->with('error', 'Anda masih memiliki pengajuan yang sedang aktif/berproses.');
        }
        // Cek apakah user ini punya Form 1 yang statusnya masih aktif
        // $hasActiveForm = Sppd::where('user_id', $user->id) // Sesuaikan 'user_id' dengan nama kolom pembuat di tabel kamu
        //     ->whereIn('status', $activeStatuses)
        //     ->exists();

        // if ($hasActiveForm) {
        //     return redirect()->route('dashboard')->with('error', 'Anda masih memiliki pengajuan yang sedang aktif/berproses.');
        // }

        // return view('sppd/create', ['user' => $user,
        return view('sppd/create', [
            'user'            => $user,
            'masterKota' => Kota::all(),
            'masterKeperluan'=> Keperluan::all(),
            'masterTransport' => Transport::all(),
        ]);
    }

    /**
     * Menyimpan Form SPPD
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Array & Conditional Manual Text
        $request->validate([
            'kota_id'            => ['required', 'exists:kota,id'],
            'durasi'             => ['required', 'integer', 'min:1', 'max:14'],
            'tugas'              => ['required', 'string'],
            
            // Validasi Multi-Select Keperluan
            'keperluan_id'       => ['required', 'array', 'min:1'],
            'keperluan_id.*'     => ['exists:keperluan,id'],
            'keperluan_lainnya'  => ['nullable', 'string', 'max:255'],

            // Validasi Multi-Select Transportasi
            'transport_id'       => ['required', 'array', 'min:1'],
            'transport_id.*'     => ['exists:transport,id'],
            'transport_lainnya'  => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        // 2. Jalankan DB Transaction
        $sppd = DB::transaction(function () use ($request, $user) {
            
            // Generate Nomor Surat Dinas
            $nextDinasId = (Dinas::max('id') ?? 0) + 1;
            $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

            $dinas = Dinas::create([
                'user_id'  => $user->id,
                'no_dinas' => $noDinas,
                'status'   => 'Draft',
            ]);

            // Simpan Data SPPD (Array ID otomatis di-cast ke JSON oleh Model)
            $sppdData = Sppd::create([
                'dinas_id'          => $dinas->id,
                'no_sppd'           => 'XXXX-HRD/RF/03-X',
                'user_id'           => $user->id,
                'kota_id'           => $request->kota_id,
                'durasi'            => $request->durasi,
                'tugas'             => $request->tugas,
                
                // Kolom JSON & Teks Kustom Hybrid
                'keperluan_id'      => $request->keperluan_id,
                'keperluan_lainnya' => $request->keperluan_lainnya,
                'transport_id'      => $request->transport_id,
                'transport_lainnya' => $request->transport_lainnya,
                
                'status'            => 'Draft',
            ]);

            $slaDueAt = now()->addHours(168);

            // Record History Approval Awal
            SppdApproval::create([
                'sppd_id'     => $sppdData->id,
                'approver_id' => null,
                'status'      => 'Draft',
                'signature'   => null,
                'sla_due_at'  => $slaDueAt,
                'approved_at' => null,
            ]);

            return $sppdData;
        });

        // 3. Redirect ke Halaman Berikutnya
        return redirect()->route('ilpd.create', ['sppd' => $sppd->id])
            ->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //             'kota_id'       => ['required', 'exists:kota,id'],
    //             'durasi'        => ['required', 'integer', 'min:1', 'max:14'],
    //             'keperluan_id'  => ['required', 'exists:keperluan,id'],
    //             'transport_id'  => ['required', 'exists:transport,id'],
    //             'tugas'         => ['required', 'string'],
    //         ]);

    //     $user = Auth::user();

    //     // 1. Jalankan Transaksi untuk Simpan Data
    //     $sppd = DB::transaction(function () use ($request, $user) {
    //         $nextDinasId = (Dinas::max('id') ?? 0) + 1;
    //         $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

    //         $dinas = Dinas::create([
    //             'user_id' => $user->id,
    //             'no_dinas' => $noDinas,
    //             'status' => 'Draft',
    //         ]);

    //         // Return objek $sppd agar bisa ditangkap oleh variabel $sppd di luar
    //         // return Sppd::create([
    //         $sppdData = Sppd::create([
    //             'dinas_id'      => $dinas->id,
    //             'no_sppd'       => 'XXXX-HRD/RF/03-X',
    //             'user_id'       => $user->id,
    //             'kota_id'       => $request->kota_id,
    //             'keperluan_id'  => $request->keperluan_id,
    //             'transport_id'  => $request->transport_id,
    //             'durasi'        => $request->durasi,
    //             'tugas'         => $request->tugas,
    //             'status'        => 'Draft',
    //         ]);

    //         $slaDueAt = now()->addHours(168);

    //         // 2. Buat Record History Approval Awal
    //         SppdApproval::create([
    //             'sppd_id'     => $sppdData->id,
    //             'approver_id' => null,     // Belum ada eksekutor approval
    //             'status'      => 'Draft',  // Mengikuti status awal pengajuan
    //             'signature'   => null,     // Belum ada tanda tangan
    //             'sla_due_at'  => $slaDueAt,
    //             'approved_at' => null,     // Belum ada timestamp persetujuan
    //         ]);

    //         return $sppdData;
    //     });

    //     // 2. Redirect dilakukan DI LUAR Transaksi membawa ID SPPD
    //     return redirect()->route('ilpd.create', ['sppd' => $sppd->id])
    //         ->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    // }

    public function edit($id)
    {
        $sppd = Sppd::findOrFail($id);

        // Ambil data pendukung untuk dropdown pilihan
        $users      = User::select('id', 'name', 'nik')->get();
        $kotas      = Kota::all();
        $keperluans = Keperluan::all();
        $transports = Transport::all();

        return view('sppd.edit', compact('sppd', 'users', 'kotas', 'keperluans', 'transports'));
    }

    /**
     * Memproses update data SPPD.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input Data
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'kota_id'      => 'required|exists:kotas,id',
            'durasi'       => 'required|integer|min:1',
            'keperluan_id' => 'required|exists:keperluans,id',
            'transport_id' => 'required|exists:transports,id',
            'tugas'        => 'required|string',
        ], [
            // Custom Error Messages (Opsional)
            'user_id.required'      => 'Pegawai wajib dipilih.',
            'kota_id.required'      => 'Kota tujuan wajib dipilih.',
            'durasi.required'       => 'Durasi waktu wajib diisi.',
            'keperluan_id.required' => 'Keperluan dinas wajib dipilih.',
            'transport_id.required' => 'Moda transportasi wajib dipilih.',
            'tugas.required'        => 'Deskripsi tugas wajib diisi.',
        ]);

        // 2. Cari Data SPPD
        $sppd = Sppd::findOrFail($id);

        // 3. Update Data di Database
        $sppd->update($validated);

        // 4. Redirect Kembali dengan Pesan Sukses
        return redirect('/dashboard')->with('success', 'Data SPPD berhasil diperbarui');
    }

    /**
     * Tampilkan detail SPPD untuk diproses oleh Manager.
     */
    public function show($id)
    {
        // Load SPPD beserta relasi yang dibutuhkan (sesuaikan nama relasi di Model)
        // $sppd = Sppd::with(['user', 'ilpd', 'dinas', 'kota'])->findOrFail($id);

        $sppd = Sppd::with([
            'user.golongan', 
            'dinas', 
            'kota', 
            // 'keperluan',
            'ilpd.dinas', 
            'ilpd.detail_ilpd' // Ambil detail_ilpd dari relasi ilpd milik sppd
        ])->findOrFail($id);

        // 2. Ambil data ilpd dari relasi $sppd (Jika ada, ambil. Jika belum ada, nilainya null)
        $ilpd = $sppd->ilpd;

        // 3. CARA CEK DEBUG YANG BENAR:
        // Bungkus dalam 1 dd() agar dua-duanya tampil di layar sekaligus!
        // dd($sppd, $ilpd);

        // return view('approval.show', compact('sppd','ilpd'));
        return view('sppd.approve', compact('sppd','ilpd'));
        // return view('sppd.approve', compact('sppd'));
    }

    /**
     * Proses Menyetujui SPPD.
     */
    public function approve(Request $request, $sppdId)
    {
        $user = Auth::user();

        // 1. Validasi TTD Manager
        if (!$user->signature) {
            return back()->with('error', 'Anda belum mengatur TTD di Profil. Silakan unggah TTD terlebih dahulu.');
        }

        // 2. Ambil data SPPD beserta relasi ILPD & approval aktifnya
        $sppd = Sppd::with(['ilpd', 'approval'])->findOrFail($sppdId);

        DB::transaction(function () use ($sppd, $user) {
            // --- A. PROSES SPPD (Form 1) ---
            // Update status master SPPD & status induk Dinas
            $sppd->update(['status' => 'Disetujui']);
            
            if ($sppd->dinas) {
                $sppd->dinas->update(['status' => 'Sedang Diproses']);
            }

            $lastApproval = SppdApproval::where('sppd_id', $sppd->id)->latest()->first();
            // Hentikan Timer SLA Form 1 (Update atau Create SppdApproval)
            // if ($sppd->approval) {
            //     $sppd->approval->update([
            //         'approver_id' => $user->id,
            //         'status'      => 'Disetujui',
            //         'signature'   => $user->signature,
            //         'approved_at' => now(), // Timer SLA Form 1 selesai
            //     ]);
            // } else {
            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => $user->id,
                'status'      => 'Disetujui',
                'signature'   => $user->signature,
                'approved_at' => now(),
            ]);

            // --- B. PROSES ILPD (Form 2) ---
            if ($sppd->ilpd) {
                // Update status ILPD
                $sppd->ilpd->update(['status' => 'Sedang Diproses']);

                // Buat rekord Approval ILPD baru & Mulai Timer SLA baru (misal 24 jam ke depan)
                IlpdApproval::create([
                    'ilpd_id'     => $sppd->ilpd->id,
                    'approver_id' => null, // Belum ada yang approve ILPD
                    'status'      => 'Sedang Diproses',
                    'signature'   => null,
                    'sla_due_at'  => now()->addHours(24), // Set deadline SLA Form 2
                    'approved_at' => null,
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Dokumen SPPD berhasil disetujui dan SLA ILPD telah diaktifkan.');
    }
    // public function approve(Request $request, $sppdId)
    // {
    //     $user = Auth::user();

    //     // 1. Validasi: Pastikan Manager sudah punya TTD di profilnya
    //     if (!$user->signature) {
    //         return back()->with('error', 'Anda belum mengatur TTD di Profil. Silakan unggah TTD di menu Profil terlebih dahulu.');
    //     }

    //     // 2. Ambil data SPPD beserta ILPD terkait
    //     $sppd = Sppd::with('ilpd')->findOrFail($sppdId);

    //     DB::transaction(function () use ($sppd, $user) {
    //         // --- A. PROSES SPPD ---
    //         // Update status di tabel sppds
    //         $sppd->update([
    //             'status' => 'Disetujui',
    //         ]);

    //         // Simpan snapshot ke tabel sppd_approvals
    //         SppdApproval::create([
    //             'sppd_id'     => $sppd->id,
    //             'approver_id' => $user->id,
    //             'status'      => 'Disetujui',
    //             'signature'   => $user->signature, // Langsung ambil dari kolom signature tabel users
    //             'approved_at' => now(),
    //         ]);

    //         // --- B. PROSES ILPD (Jika SPPD memiliki relasi ke ILPD) ---
    //         if ($sppd->ilpd) {
    //             // Update status di tabel ilpds
    //             $sppd->ilpd->update([
    //                 'status' => 'Sedang Diproses',
    //             ]);

    //             // Simpan snapshot ke tabel ilpd_approvals
    //             IlpdApproval::create([
    //                 'ilpd_id'     => $sppd->ilpd->id,
    //                 'approver_id' => $user->id,
    //                 'status'      => 'Disetujui Manager',
    //                 'signature'   => $user->signature, // Gunakan TTD dari profil user yang sama
    //                 'approved_at' => now(),
    //             ]);
    //         }
    //     });

    //     return redirect()->route('dashboard')->with('success', 'Dokumen SPPD dan ILPD berhasil disetujui.');
    // }
}