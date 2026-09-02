<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
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

        // Gunakan Database Transaction agar aman
        DB::transaction(function () use ($request, $user) {
            
            // Hitung nomor urut berikutnya dari tabel Dinas
            $nextDinasId = (Dinas::max('id') ?? 0) + 1;
            $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

            // 1. Buat record parent Dinas dulu (Jangan lupa nama kolom 'no_dinas')
            $dinas = Dinas::create([
                'no_dinas' => $noDinas,
            ]);

            $sppd = Sppd::create([
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

            return redirect()->route('ilpd.create', $sppd->id)->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
        });
    }
}