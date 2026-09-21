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
    // public function index()
    // {
    //     $user = auth()->user();

    //     // Ambil data sesuai user
    //     $pengajuanSaya = Sppd::with(['dinas', 'user', 'kota', 'ilpd'])
    //         ->where('user_id', $user->id)
    //         ->latest()
    //         ->take(5)
    //         ->get();
    //     // $approvalManager = Sppd::where('user_id', $user->id)->where('status', 'menunggu_approval')->get();
    //     $pengajuanGa = Sppd::latest()->get();

    //     return view('riwayat', compact('pengajuanSaya', 'pengajuanGa'));
    //     // return view('dashboard', compact('pengajuanSaya', 'approvalManager', 'pengajuanGa'));
    // }

    public function index(Request $request)
    {
        // $userId = auth()->id();
        $user = auth()->user();

        // 1. Query Utama untuk List Sisi Kiri (dinas + sppd + ilpd)
        // $query = Dinas::with(['sppd.kota','sppd.user','sppd_approvals', 'ilpd.approvals',])
        //     ->whereHas('sppd', function ($query) use ($userId) {
        //         $query->where('user_id', $userId);
        //     });
            // ->whereIn('status', ['Disetujui', 'Selesai']);
            // ->get();
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
        // ->whereHas('sppd', function ($query) use ($userId) {
        //     $query->where('user_id', $userId);
        // });

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
        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        $daftarDinas = $query->orderBy('dinas.created_at', 'desc')->paginate(10);

        $dinasList = Dinas::with(['sppd.sppd_approval', 'ilpd.ilpd_approval'])->get();

        return view('riwayat', compact('daftarDinas', 'dinasList'));
        // return view('riwayat', compact('daftarDinas', 'sppd', 'flowSteps', 'currentStep'));
    }

    // public function show($id)
    // {
    //     $sppd = Sppd::with(['approvals', 'ilpd.approvals'])->findOrFail($id);

    //     // 1. Ambil status terbaru dari SPPD dan ILPD
    //     $latestSppdApproval = $sppd->approvals()->latest()->first();
    //     $latestIlpdApproval = $sppd->ilpd ? $sppd->ilpd->approvals()->latest()->first() : null;

    //     // 2. Tentukan posisi Step Aktif saat ini (1 sampai 5)
    //     $currentStep = 1; // Default Step 1: Draft SPPD

    //     if ($sppd->ilpd) {
    //         $currentStep = 2; // ILPD sudah dibuat (Pengajuan ILPD)
    //     }

    //     if ($latestSppdApproval && $latestSppdApproval->status === 'Menunggu Approval') {
    //         $currentStep = 3; // Menunggu Manager
    //     }

    //     if ($latestIlpdApproval && $latestIlpdApproval->status === 'Menunggu Approval') {
    //         $currentStep = 4; // Menunggu GA / Finance (Upload Tiket & BBM)
    //     }

    //     if ($latestIlpdApproval && $latestIlpdApproval->status === 'Disetujui') {
    //         $currentStep = 5; // Selesai / Disetujui Semua
    //     }

    //     // 3. Susun Array Mapping Step
    //     $flowSteps = [
    //         1 => ['role' => 'Draft SPPD', 'desc' => 'Pembuatan draft pengajuan'],
    //         2 => ['role' => 'Pengajuan ILPD', 'desc' => 'Pengisian form rincian biaya'],
    //         3 => ['role' => 'Manager', 'desc' => 'Pemeriksaan pengajuan'],
    //         4 => ['role' => 'General Affair / HRGA', 'desc' => 'Pemeriksaan budget dan tiket'],
    //         5 => ['role' => 'General Manager / Completed', 'desc' => 'Persetujuan akhir selesai'],
    //     ];

    //     return view('riwayat', compact('sppd', 'flowSteps', 'currentStep'));
    // }

            // $sppd = Sppd::with(['approvals', 'ilpd.approvals'])->findOrFail($id);

        // // 1. Ambil status terbaru dari SPPD dan ILPD
        // $latestSppdApproval = $sppd->approvals()->latest()->first();
        // $latestIlpdApproval = $sppd->ilpd ? $sppd->ilpd->approvals()->latest()->first() : null;

        // // 2. Tentukan posisi Step Aktif saat ini (1 sampai 5)
        // $currentStep = 1; // Default Step 1: Draft SPPD

        // if ($sppd->ilpd) {
        //     $currentStep = 2; // ILPD sudah dibuat (Pengajuan ILPD)
        // }

        // if ($latestSppdApproval && $latestSppdApproval->status === 'Menunggu Approval') {
        //     $currentStep = 3; // Menunggu Manager
        // }

        // if ($latestIlpdApproval && $latestIlpdApproval->status === 'Menunggu Approval') {
        //     $currentStep = 4; // Menunggu GA / Finance (Upload Tiket & BBM)
        // }

        // if ($latestIlpdApproval && $latestIlpdApproval->status === 'Disetujui') {
        //     $currentStep = 5; // Selesai / Disetujui Semua
        // }

        // // 3. Susun Array Mapping Step
        // $flowSteps = [
        //     1 => ['role' => 'Draft SPPD', 'desc' => 'Pembuatan draft pengajuan'],
        //     2 => ['role' => 'Pengajuan ILPD', 'desc' => 'Pengisian form rincian biaya'],
        //     3 => ['role' => 'Manager', 'desc' => 'Pemeriksaan pengajuan'],
        //     4 => ['role' => 'General Affair / HRGA', 'desc' => 'Pemeriksaan budget dan tiket'],
        //     5 => ['role' => 'General Manager / Completed', 'desc' => 'Persetujuan akhir selesai'],
        // ];
}
