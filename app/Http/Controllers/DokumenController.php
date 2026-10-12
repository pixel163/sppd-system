<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\Sppd;
use App\Models\Ilpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\DinasStatus;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $user   = auth()->user();

        $query = Dinas::with(['sppd.kota', 'ilpd'])
            ->whereIn('status', [DinasStatus::DISETUJUI, DinasStatus::SELESAI]);
        
        if ($user?->jabatan?->name !== 'HRGA') { 
            $query->whereHas('sppd', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_dinas', 'like', "%{$search}%")
                ->orWhereHas('sppd.kota', function ($qKota) use ($search) {
                    $qKota->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        $selectedId = $request->get('selected_id');
        if (!$selectedId && $daftarDinas->isNotEmpty()) {
            $selectedId = $daftarDinas->first()->id;
        }

        $detailData = null;
        if ($selectedId) {
            $dinas = Dinas::with([
                'sppd.kota',
                'sppd.user',
                'sppd.sppd_approval.sppd_approver',
                'ilpd.sppd.kota',
                'ilpd.ilpd_approval',
                'ilpd.laporan',
                'ilpd.detail_ilpd',
                'ilpd.tiket'
            ])
            ->where('id', $selectedId)
            ->first();

            if ($dinas) {
                
            $sppdDoc = $dinas->sppd ? collect([$dinas->sppd]) : collect();
                $ilpdDoc = $dinas->ilpd ? collect([$dinas->ilpd]) : collect();
                
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