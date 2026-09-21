<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
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
        $pengajuanSaya = Sppd::with(['dinas', 'user', 'kota', 'ilpd.detail_ilpd', 'ilpd.tiket'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        // $pengajuanSaya = Sppd::with(['dinas','user','kota','keperluan','transport','ilpd.detail_ilpd','ilpd.tiket'])
        // ->where('user_id', $user->id)
        // ->latest()
        // ->take(5)
        // ->get();
        
        // Manager
        $approvalManager = Sppd::with(['dinas','user','kota','ilpd'])
            ->where('status', 'Menunggu Approval')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department->id);
            })
            ->latest()
            ->get();
        
        // General Affair
        // $pengajuanGa = Sppd::with(['dinas','user','keperluan','transport','kota','ilpd.detail_ilpd','ilpd.tiket','approval'])
        $pengajuanGa = Sppd::with(['dinas','user','kota','ilpd.detail_ilpd','ilpd.tiket','approval'])
        ->latest()
        ->take(5)
        ->get();

        $draft = Dinas::where('status', 'Draft')->count();

        $Disetujui = Dinas::where('status', 'Disetujui')->count();

        $menungguApproval = Dinas::where('status', 'Menunggu Approval')->count();

        $sedangDiproses = Dinas::where('status', 'Sedang Diproses')->count();

        $selesai = Dinas::where('status', 'Selesai')->count();

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        return view('dashboard', compact('pengajuanSaya' ,'approvalManager', 'pengajuanGa', 'pengajuanGa','draft', 'Disetujui', 'menungguApproval', 'sedangDiproses', 'selesai', 'dinasList'));}
}
