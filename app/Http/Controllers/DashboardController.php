<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
use App\Models\Dinas;
use App\Models\Kota;
use App\Models\Department;
use App\Models\Keperluan;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\SppdStatus;
use App\Enums\DinasStatus;

class DashboardController extends Controller
{

    public function index(Request $request)
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

        $departemens = Department::all();
        
        $pengajuanGa = Sppd::with(['dinas','user','user.department','kota','ilpd.detail_ilpd','ilpd.tiket','approval', 'sppd_approval.sppd_approver', 'user.jabatan','ilpd.ilpd_approval','ilpd.approval','ilpd.laporan'])
            ->when($request->department_id, function($query) use ($request) {
                $query->whereHas('user', function($q) use ($request) {
                    // $query->whereHas('sppd.user', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                })->orWhereHas('user', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            })
            ->latest()
            ->take(6)
            // ->take(5)
            ->get();

        $draft = Dinas::where('status', DinasStatus::DRAFT)->count();

        $Disetujui = Dinas::where('status', DinasStatus::DISETUJUI)->count();

        $menungguApproval = Dinas::where('status', DinasStatus::MENUNGGU_APPROVAL)->count();

        $sedangDiproses = Dinas::where('status', DinasStatus::SEDANG_DIPROSES)->count();

        $selesai = Dinas::where('status', DinasStatus::SELESAI)->count();

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        $keperluans = Keperluan::all();
        $transports = Transport::all();

        return view('dashboard', compact('pengajuanSaya' ,'approvalManager', 'pengajuanGa', 'pengajuanGa','draft', 'Disetujui', 'menungguApproval', 'sedangDiproses', 'selesai', 'dinasList', 'keperluans', 'transports','departemens'));}
}
