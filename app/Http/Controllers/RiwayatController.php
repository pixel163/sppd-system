<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Dinas::with([
            'sppd.kota',
            'sppd.user',
            'sppd.sppd_approval',
            'ilpd.ilpd_approval',
        ]);

        if ($user->jabatan->name !== 'HRGA') {
            $query->whereHas('sppd', function ($q) use ($user) {
                $q->where('user_id', $user->id);
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

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        return view('riwayat', compact('daftarDinas', 'dinasList'));
    }
}
