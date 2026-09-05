<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        // ambil data berdasarkan user
        $pengajuanSaya = Sppd::with(['dinas','user','kota','ilpd'])
        ->where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get();
        
        // Manager
        $approvalManager = Sppd::with(['dinas','user','kota','ilpd'])
            ->where('status', 'Menunggu Approval')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department->id);
            })
            ->latest()
            ->get();
        
        // $pengajuanGa = Sppd::latest()->get();

        return view('dashboard', compact('pengajuanSaya' ,'approvalManager'));
        // return view('dashboard', compact('approvalManager', 'pengajuanGa'));
    }
}
