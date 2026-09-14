<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\Ilpd;
use App\Models\Dinas;
use App\Models\Kota;
use App\Models\Keperluan;
use App\Models\Transport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        // ambil data berdasarkan user
        $pengajuanSaya = Sppd::with(['dinas','user','kota','keperluan','transport','ilpd','ilpd.tiket'])
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
        
        // General Affair
        // $pengajuanGa = Ilpd::where('status', 'Sedang Diproses')
        //     ->latest()
        //     ->get();
        // $pengajuanGa = Ilpd::with(['dinas','sppd.user','kota','sppd','tiket'])
        $pengajuanGa = Sppd::with(['dinas','user','kota','ilpd','ilpd.tiket'])
        ->latest()
        ->take(5)
        ->get();

        $draft = Dinas::where('status', 'Draft')->count();

        $Disetujui = Dinas::where('status', 'Disetujui')->count();

        $menungguApproval = Dinas::where('status', 'Menunggu Approval')->count();

        $sedangDiproses = Dinas::where('status', 'Sedang Diproses')->count();

        $selesai = Dinas::where('status', 'Selesai')->count();

        return view('dashboard', compact('pengajuanSaya' ,'approvalManager', 'pengajuanGa', 'pengajuanGa','draft', 'Disetujui', 'menungguApproval', 'sedangDiproses', 'selesai'));}
}
