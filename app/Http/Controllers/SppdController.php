<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Dinas;
use App\Models\Kota;
use App\Models\Keperluan;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SppdController extends Controller
{
    /**
     * Menampilkan form SPPD
     */
    public function create()
    {
        $user = Auth::user();

        return view('sppd/create', ['user' => $user,
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
        $request->validate([
                'kota_id'       => ['required', 'exists:kota,id'],
                'durasi'        => ['required', 'integer', 'min:1', 'max:14'],
                'keperluan_id'  => ['required', 'exists:keperluan,id'],
                'transport_id'  => ['required', 'exists:transport,id'],
                'tugas'         => ['required', 'string'],
            ]);

        $user = Auth::user();

        // 1. Jalankan Transaksi untuk Simpan Data
        $sppd = DB::transaction(function () use ($request, $user) {
            $nextDinasId = (Dinas::max('id') ?? 0) + 1;
            $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

            $dinas = Dinas::create([
                'no_dinas' => $noDinas,
            ]);

            // Return objek $sppd agar bisa ditangkap oleh variabel $sppd di luar
            return Sppd::create([
                'dinas_id'      => $dinas->id,
                'no_sppd'       => 'XXXX-HRD/RF/03-X',
                'user_id'       => $user->id,
                'kota_id'       => $request->kota_id,
                'keperluan_id'  => $request->keperluan_id,
                'transport_id'  => $request->transport_id,
                'durasi'        => $request->durasi,
                'tugas'         => $request->tugas,
                'status'        => 'Menunggu Approval',
            ]);
        });

        // 2. Redirect dilakukan DI LUAR Transaksi membawa ID SPPD
        return redirect()->route('ilpd.create', ['sppd' => $sppd->id])
            ->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'kota_id'       => ['required', 'exists:kota,id'],
    //         'durasi'        => ['required', 'integer', 'min:1', 'max:14'],
    //         'keperluan_id'  => ['required', 'exists:keperluan,id'],
    //         'transport_id'  => ['required', 'exists:transport,id'],
    //         'tugas'         => ['required', 'string'],
    //     ]);

    //     $user = Auth::user();

    //     // Gunakan Database Transaction agar aman
    //     $sppd = DB::transaction(function () use ($request, $user) {
    //         // Hitung nomor urut berikutnya dari tabel Dinas
    //         $nextDinasId = (Dinas::max('id') ?? 0) + 1;
    //         $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

    //         // 1. Buat record parent Dinas dulu (Jangan lupa nama kolom 'no_dinas')
    //         $dinas = Dinas::create([
    //             'no_dinas' => $noDinas,
    //         ]);

    //         return Sppd::create([
    //             'dinas_id'      => $dinas->id,
    //             'no_sppd'       => 'XXXX-HRD/RF/03-X',
    //             'user_id'       => $user->id,
    //             'kota_id'       => $request->kota_id,
    //             'keperluan_id'  => $request->keperluan_id,
    //             'transport_id'  => $request->transport_id,
    //             'durasi'        => $request->durasi,
    //             'tugas'         => $request->tugas,
    //             'status'        => 'Menunggu Approval',
    //         ]);

    //         return redirect()->route('ilpd.create', ['sppd' => $sppd->id])->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    //     });
    // }

    // public function edit($id)
    // {
    //     // Load SPPD beserta relasi yang dibutuhkan (sesuaikan nama relasi di Model)
    //     $sppd = Sppd::with(['user', 'ilpd', 'dinas', 'kota'])->findOrFail($id);

    //     return view('sppd.edit', compact('sppd'));
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
        $sppd = Sppd::with(['user', 'ilpd', 'dinas', 'kota'])->findOrFail($id);

        return view('sppd.approve', compact('sppd'));
    }

    /**
     * Proses Menyetujui SPPD.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'ttd_file' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $sppd = Sppd::findOrFail($id);

        $ttdPath = null;
        if ($request->hasFile('ttd_file')) {
            // Simpan file ke folder storage/app/public/ttd
            $ttdPath = $request->file('ttd_file')->store('ttd', 'public');
        }

        DB::transaction(function () use ($sppd, $request, $ttdPath) {
            // 1. Update status di tabel sppds
            $sppd->update([
                'status' => 'Disetujui',
            ]);

            // 2. Simpan jejak approval di tabel sppd_approvals
            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => Auth::id(), // Ambil ID user yang sedang login
                'status'      => 'Disetujui',
                'signature'   => $ttdPath,
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Dokumen SPPD berhasil disetujui.');
    }
}