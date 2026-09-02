<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        // Ambil data sesuai role
        // Eager load relasi dinas, user, kota, dan ilpd
        $pengajuanStaff = Sppd::with(['dinas', 'user', 'kota', 'ilpd'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        $approvalManager = Sppd::where('user_id', $user->id)->where('status', 'menunggu_approval')->get();
        // $pengajuanGa = Sppd::latest()->get();

        return view('dashboard', compact('pengajuanStaff', 'approvalManager'));
        // return view('dashboard', compact('pengajuanStaff', 'approvalManager', 'pengajuanGa'));
    }
}
