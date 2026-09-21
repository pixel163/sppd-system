<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\Sppd;
use App\Models\Ilpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $user   = auth()->user();

        // 1. Query Utama untuk List Sisi Kiri (dinas + sppd + ilpd)
        $query = Dinas::with(['sppd.kota', 'ilpd'])
            // ->whereHas('sppd', function ($query) use ($userId) {
            //     $query->where('user_id', $userId);
            // })
            ->whereIn('status', ['Disetujui', 'Selesai']);
        
        if ($user?->jabatan?->name !== 'HRGA') { 
            $query->whereHas('sppd', function ($q) use ($userId) {
                $q->where('user_id', $userId); // Gunakan $userId langsung di sini
            });
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_dinas', 'like', "%{$search}%")
                ->orWhereHas('sppd.kota', function ($qKota) use ($search) {
                    $qKota->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        // 2. Tentukan ID mana yang sedang dipilih/diklik
        $selectedId = $request->get('selected_id');
        if (!$selectedId && $daftarDinas->isNotEmpty()) {
            $selectedId = $daftarDinas->first()->id;
        }

        // 3. Ambil Detail Sisi Kanan (Detail Dinas, Form 1 SPPD, Form 2 ILPD, & Tiket)
        $detailData = null;
        if ($selectedId) {
            // Data Utama Dinas + SPPD + ILPD
            $dinas = Dinas::with([
                'sppd.kota',
                'sppd.user',
                'sppd.keperluan',
                'sppd.transport',
                'ilpd.sppd.kota',
                'ilpd.sppd.transport',
                'ilpd.sppd.keperluan',
                'ilpd.detail_ilpd',
                'ilpd.tiket' // Menarik ILPD beserta Tiket sekaligus
            ])
            ->where('id', $selectedId)
            // ->whereHas('sppd', function ($query) use ($userId) {
            //     $query->where('user_id', $userId);
            // })
            ->first();

            if ($dinas) {
                
            $sppdDoc = $dinas->sppd ? collect([$dinas->sppd]) : collect();
                $ilpdDoc = $dinas->ilpd ? collect([$dinas->ilpd]) : collect();
                
                // Mengambil tiket dari relasi ILPD (jika ILPD punya banyak tiket)
                $tiketDoc = $dinas->ilpd && $dinas->ilpd->tiket 
                    ? (is_iterable($dinas->ilpd->tiket) ? $dinas->ilpd->tiket : collect([$dinas->ilpd->tiket]))
                    : collect();

                $detailData = [
                    'dinas' => $dinas,
                    'sppd'  => $sppdDoc,
                    'ilpd'  => $ilpdDoc,
                    'tiket' => $tiketDoc,
                ];

            }
        }

        return view('dokumen', compact('daftarDinas', 'detailData', 'selectedId'));
    }
}