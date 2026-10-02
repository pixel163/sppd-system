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
            'sppd.sppd_approval', // 👈 Ditambahkan untuk riwayat approval SPPD
            'ilpd.ilpd_approval', // 👈 Ditambahkan untuk riwayat approval ILPD
        ]);

        if ($user->jabatan->name !== 'HRGA') { // Sesuaikan 'GA' dengan nama role/penamaan di sistem kamu
            $query->whereHas('sppd', function ($q) use ($user) {
                $q->where('user_id', $user->id);
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
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        return view('riwayat', compact('daftarDinas', 'dinasList'));
    }
}
