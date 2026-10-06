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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\SppdStatus;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        $pengajuanSaya = Sppd::with(['dinas', 'approval', 'sppd_approval.sppd_approver', 'user', 'kota', 'ilpd.detail_ilpd', 'ilpd.laporan', 'ilpd.tiket', 'ilpd.approval'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $approvalManager = Sppd::with(['dinas','user','kota','ilpd'])
            ->where('status', SppdStatus::MENUNGGU_APPROVAL)
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->latest()
            ->get();
        
        $pengajuanGa = Sppd::with(['dinas','user','kota','ilpd.detail_ilpd','ilpd.tiket','approval', 'sppd_approval.sppd_approver', 'user.jabatan','ilpd.ilpd_approval','ilpd.approval'])
        ->latest()
        ->take(5)
        ->get();

        $draft = Dinas::where('status', 'Draft')->count();

        $Disetujui = Dinas::where('status', 'Disetujui')->count();

        $menungguApproval = Dinas::where('status', 'Menunggu Approval')->count();

        $sedangDiproses = Dinas::where('status', 'Sedang Diproses')->count();

        $selesai = Dinas::where('status', 'Selesai')->count();

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        $keperluans = Keperluan::all();
        $transports = Transport::all();

        return view('dashboard', compact('pengajuanSaya' ,'approvalManager', 'pengajuanGa', 'pengajuanGa','draft', 'Disetujui', 'menungguApproval', 'sedangDiproses', 'selesai', 'dinasList', 'keperluans', 'transports'));}
}
