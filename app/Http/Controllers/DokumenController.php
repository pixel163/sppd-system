<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // 1. Query Utama untuk List Sisi Kiri (dinas + sppd + ilpd)
        $query = DB::table('dinas')
            ->leftJoin('sppd', 'dinas.id', '=', 'sppd.dinas_id')  // Sesuaikan FK jika berbeda
            ->leftJoin('ilpd', 'dinas.id', '=', 'ilpd.dinas_id') 
            ->leftJoin('kota', 'sppd.kota_id', '=', 'kota.id') // Sesuaikan FK jika berbeda
            ->where('sppd.user_id', $userId)
            ->select(
                'dinas.id',
                'dinas.no_dinas',
                'dinas.status',
                'kota.name as kota_tujuan',
                'ilpd.tanggal_awal',     // Kolom tanggal dari tabel ilpd
                'ilpd.tanggal_akhir'
            );

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('dinas.no_dinas', 'like', "%{$search}%")
                  ->orWhere('kota.name', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('dinas.status', $request->status);
        }

        // Dapatkan data paginasi
        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        // 2. Tentukan ID mana yang sedang dipilih/diklik
        // Jika ada query parameter ?selected_id=X gunakan itu, jika tidak ada, default ke item pertama
        $selectedId = $request->get('selected_id');
        if (!$selectedId && $daftarDinas->count() > 0) {
            $selectedId = $daftarDinas->first()->id;
        }

        // 3. Ambil Detail Sisi Kanan (Detail Dinas, Form 1, & Form 2)
        $detailData = null;
        if ($selectedId) {
            // Data Utama Dinas + SPPD + ILPD
            $dinas = DB::table('dinas')
                ->leftJoin('sppd', 'dinas.id', '=', 'sppd.dinas_id')
                ->leftJoin('ilpd', 'dinas.id', '=', 'ilpd.dinas_id')
                ->leftJoin('kota', 'sppd.kota_id', '=', 'kota.id')
                ->leftJoin('users', 'sppd.user_id', '=', 'users.id')
                ->select(
                    'dinas.id',
                    'dinas.no_dinas',
                    'dinas.status',
                    'kota.name as kota_tujuan',
                    'ilpd.tanggal_awal',
                    'ilpd.tanggal_akhir',
                    'users.name as name',
                    'sppd.created_at as tgl_pengajuan'
                )
                ->where('dinas.id', $selectedId)
                ->where('sppd.user_id', $userId)
                ->first();

            if ($dinas) {
                // Ambil Data Form 1 (Dokumen)
                $form1 = DB::table('sppd') // Sesuaikan nama tabel Form 1
                    ->where('dinas_id', $selectedId)
                    ->get();

                // Ambil Data Form 2 (Tiket)
                $form2 = DB::table('ilpd') // Sesuaikan nama tabel Form 2
                    ->where('dinas_id', $selectedId)
                    ->get();

                $detailData = [
                    'dinas' => $dinas,
                    'sppd' => $form1,
                    'ilpd' => $form2,
                ];
            }
        }

        return view('dokumen', compact('daftarDinas', 'detailData', 'selectedId'));
    }
}