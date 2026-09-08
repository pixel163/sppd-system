<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data sesuai user
        $pengajuanSaya = Sppd::with(['dinas', 'user', 'kota', 'ilpd'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        // $approvalManager = Sppd::where('user_id', $user->id)->where('status', 'menunggu_approval')->get();
        // $pengajuanGa = Sppd::latest()->get();

        return view('riwayat', compact('pengajuanSaya'));
        // return view('dashboard', compact('pengajuanSaya', 'approvalManager', 'pengajuanGa'));
    }
}
