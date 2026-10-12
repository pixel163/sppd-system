<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\Ilpd;
use App\Models\SppdApproval;
use App\Models\IlpdApproval;
use App\Models\Dinas;
use App\Models\Area;
use App\Models\Kota;
use App\Models\Unit;
use App\Models\Keperluan;
use App\Models\Transport;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\SppdStatus;
use App\Enums\DinasStatus;
use App\Enums\SppdApprovalStatus;
use App\Enums\IlpdStatus;
use App\Enums\IlpdApprovalStatus;

class SppdController extends Controller
{
    // public function getKota($area_id)
    // {
    //     $kota = Kota::where('area_id', $area_id)->get();
    //     return response()->json($kota);
    // }

    // public function getUnit($kota_id)
    // {
    //     $unit = Unit::where('kota_id', $kota_id)->get();
    //     return response()->json($unit);
    // }

    public function getKota($areaId)
    {
        return response()->json(
            Kota::where('area_id', $areaId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
        );
    }

    public function getUnit($kotaId)
    {
        return response()->json(
            Unit::where('kota_id', $kotaId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
        );
    }

    public function create()
    {
        $user = Auth::user();

        $activeStatuses = [SppdStatus::DRAFT, SppdStatus::MENUNGGU_APPROVAL];
        // $activeStatuses = [SppdStatus::DRAFT, SppdStatus::MENUNGGU_APPROVAL, SppdStatus::DISETUJUI];

        $lastForm = Sppd::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastForm && in_array($lastForm->status, $activeStatuses)) {
            return redirect()->route('dashboard')->with('error', 'Anda masih memiliki pengajuan yang sedang aktif/berproses.');
        }

        // $areas = Area::all();

        return view('sppd/create', [
            'user'            => $user,
            'masterKota'      => Kota::where('is_active', true)->get(),
            'masterKeperluan' => Keperluan::where('is_active', true)->get(),
            'masterTransport' => Transport::where('is_active', true)->get(),
            'areas'           => Area::where('is_active', true)->get(),
            // 'masterKota' => Kota::all(),
            // 'masterKeperluan'=> Keperluan::all(),
            // 'masterTransport' => Transport::all(),
            // 'areas' => Area::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id'            => ['required', 'exists:area,id'],
            'kota_id'            => ['required', 'exists:kota,id'],
            'unit_ids'            => ['nullable', 'array'],
            'unit_ids.*'         => ['exists:unit,id'],
            'durasi'             => ['required', 'integer', 'min:1', 'max:14'],
            'tugas'              => ['required', 'string'],

            'keperluan_id'       => ['required_without:keperluan_lainnya', 'array'],
            'keperluan_id.*'     => ['exists:keperluan,id'],
            'keperluan_lainnya'  => ['nullable', 'string', 'max:255'],

            'transport_id'       => ['required_without:transport_lainnya', 'array'],
            'transport_id.*'     => ['exists:transport,id'],
            'transport_lainnya'  => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        $sppd = DB::transaction(function () use ($request, $user) {
            
            $masterSla = now()->addHours(168);

            $nextDinasId = (Dinas::max('id') ?? 0) + 1;
            $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

            $dinas = Dinas::create([
                'user_id'     => $user->id,
                'no_dinas'    => $noDinas,
                'status'      => DinasStatus::DRAFT,
                'overall_sla' => $masterSla,
                'status_sla'  => 'IN_PROGRESS',
            ]);

            $sppdData = Sppd::create([
                'dinas_id'          => $dinas->id,
                'no_sppd'           => 'GDSK-HRD/FR/04-Y',
                'user_id'           => $user->id,
                'area_id'           => $request->area_id,
                'kota_id'           => $request->kota_id,
                'unit_id'           => $request->unit_ids ?? [],
                'durasi'            => $request->durasi,
                'tugas'             => $request->tugas,
                'keperluan_id'      => $request->keperluan_id,
                'keperluan_lainnya' => $request->keperluan_lainnya,
                'transport_id'      => $request->transport_id,
                'transport_lainnya' => $request->transport_lainnya,
                'status'            => SppdStatus::DRAFT,
            ]);

            // Jika Anda memiliki relasi many-to-many untuk unit di tabel pivot (misal: sppd_unit)
            if ($request->filled('unit_id')) {
                $sppdData->units()->sync($request->unit_id);
            }

            $form1SlaDueAt = now()->addHours(48);

            SppdApproval::create([
                'sppd_id'     => $sppdData->id,
                'approver_id' => null,
                'status'      => SppdApprovalStatus::DRAFT,
                'signature'   => null,
                'sla_due_at'  => $form1SlaDueAt,
                'approved_at' => null,
            ]);

            return $sppdData;
        });

        return redirect()->route('ilpd.create', ['sppd' => $sppd->id])
            ->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'kota_id'            => ['required', 'exists:kota,id'],
    //         'durasi'             => ['required', 'integer', 'min:1', 'max:14'],
    //         'tugas'              => ['required', 'string'],

    //         // Menggunakan required_without agar valid jika 'keperluan_lainnya' diisi
    //         'keperluan_id'       => ['required_without:keperluan_lainnya', 'array'],
    //         'keperluan_id.*'     => ['exists:keperluan,id'],
    //         'keperluan_lainnya'  => ['nullable', 'string', 'max:255'],

    //         'transport_id'       => ['required_without:transport_lainnya', 'array'],
    //         'transport_id.*'     => ['exists:transport,id'],
    //         'transport_lainnya'  => ['nullable', 'string', 'max:255'],
    //     ]);

    //     $user = Auth::user();

    //     $sppd = DB::transaction(function () use ($request, $user) {
            
    //         $masterSla = now()->addHours(168);

    //         $nextDinasId = (Dinas::max('id') ?? 0) + 1;
    //         $noDinas = 'DINAS-' . date('Y') . '-' . str_pad($nextDinasId, 5, '0', STR_PAD_LEFT);

    //         $dinas = Dinas::create([
    //             'user_id'  => $user->id,
    //             'no_dinas' => $noDinas,
    //             'status' => DinasStatus::DRAFT,
    //             'overall_sla' => $masterSla,
    //             'status_sla'  => 'IN_PROGRESS',
    //         ]);

    //         $sppdData = Sppd::create([
    //             'dinas_id'          => $dinas->id,
    //             'no_sppd'           => 'GDSK-HRD/FR/04-Y',
    //             'user_id'           => $user->id,
    //             'kota_id'           => $request->kota_id,
    //             'durasi'            => $request->durasi,
    //             'tugas'             => $request->tugas,
    //             'keperluan_id'      => $request->keperluan_id,
    //             'keperluan_lainnya' => $request->keperluan_lainnya,
    //             'transport_id'      => $request->transport_id,
    //             'transport_lainnya' => $request->transport_lainnya,
    //             'status'            => SppdStatus::DRAFT,
    //         ]);

    //         $form1SlaDueAt = now()->addHours(48);

    //         SppdApproval::create([
    //             'sppd_id'     => $sppdData->id,
    //             'approver_id' => null,
    //             'status'      => SppdApprovalStatus::DRAFT,
    //             'signature'   => null,
    //             'sla_due_at'  => $form1SlaDueAt,
    //             'approved_at' => null,
    //         ]);

    //         return $sppdData;
    //     });

    //     return redirect()->route('ilpd.create', ['sppd' => $sppd->id])
    //         ->with('success', 'Form SPPD berhasil disimpan. Silakan lanjut mengisi Form Perizinan.');
    // }

    public function edit($id)
    {
        $sppd = Sppd::findOrFail($id);

        $users      = User::select('id', 'name', 'nik')->get();
        $kotas      = Kota::all();
        $keperluans = Keperluan::all();
        $transports = Transport::all();

        return view('sppd.edit', compact('sppd', 'users', 'kotas', 'keperluans', 'transports'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'kota_id'      => 'required|exists:kotas,id',
            'durasi'       => 'required|integer|min:1',
            'keperluan_id' => 'required|exists:keperluans,id',
            'transport_id' => 'required|exists:transports,id',
            'tugas'        => 'required|string',
        ], [
            'user_id.required'      => 'Pegawai wajib dipilih.',
            'kota_id.required'      => 'Kota tujuan wajib dipilih.',
            'durasi.required'       => 'Durasi waktu wajib diisi.',
            'keperluan_id.required' => 'Keperluan dinas wajib dipilih.',
            'transport_id.required' => 'Moda transportasi wajib dipilih.',
            'tugas.required'        => 'Deskripsi tugas wajib diisi.',
        ]);

        $sppd = Sppd::findOrFail($id);

        $sppd->update($validated);

        return redirect('/dashboard')->with('success', 'Data SPPD berhasil diperbarui');
    }

    public function shows($id)
    {
        $sppd = Sppd::with([
            'user.golongan', 
            'dinas', 
            'kota', 
            'ilpd.dinas', 
            'ilpd.detail_ilpd'
        ])->findOrFail($id);

        $ilpd = $sppd->ilpd;

        return view('sppd.approve', compact('sppd','ilpd'));
    }

    public function show($id)
    {
        $sppd = Sppd::with([
            'user.golongan', 
            'dinas', 
            'kota', 
            'ilpd.dinas', 
            'ilpd.detail_ilpd'
        ])->findOrFail($id);

        $ilpd = $sppd->ilpd;

        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $transports = Transport::all(); 
        $keperluans = Keperluan::all();

        // $tarifs = Tarif::with('golongan')
        $tarif = Tarif::with('golongan')
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();
            // ->get();

        return view('approval.manager', compact('sppd','ilpd','transports','keperluans','tarif'));
        // return view('approval.manager', compact('sppd','ilpd','transports','keperluans','tarifs'));
    }

    // public function approve(Request $request, $sppdId)
    // {
    //     $user = Auth::user();

    //     if (!$user->signature) {
    //         return back()->with('error', 'Anda belum mengatur TTD di Profil. Silakan unggah TTD terlebih dahulu.');
    //     }

    //     $request->validate([
    //         'durasi' => 'nullable|integer|min:1',
    //         'tugas'  => 'nullable|string|max:1000',
    //     ]);

    //     // $sppd = Sppd::with(['ilpd', 'approval'])->findOrFail($sppdId);
    //     $sppd = Sppd::with(['ilpd', 'approval', 'dinas'])->findOrFail($sppdId);

    //     DB::transaction(function () use ($sppd, $user, $request) {
    //         $sppdData->update(['status' => SppdStatus::DISETUJUI]);

    //         if ($request->has('durasi')) {
    //             $sppdData['durasi'] = (int) $request->input('durasi');
    //         }

    //         if ($request->has('tugas')) {
    //             $sppdData['tugas'] = $request->input('tugas');
    //         }

    //         // Update data SPPD
    //         $sppd->update($sppdData);
            
    //         if ($sppd->dinas) {
    //             $sppd->dinas->update(['status' => DinasStatus::SEDANG_DIPROSES]);
    //         }

    //         $lastApproval = SppdApproval::where('sppd_id', $sppd->id)->latest()->first();

    //         SppdApproval::create([
    //             'sppd_id'     => $sppd->id,
    //             'approver_id' => $user->id,
    //             'status'      => SppdApprovalStatus::DISETUJUI,
    //             'signature'   => $user->signature,
    //             'sla_due_at'  => $sppd->dinas->overall_sla,
    //             'approved_at' => now(),
    //         ]);

    //         if ($sppd->ilpd) {
    //             $sppd->ilpd->update(['status' => IlpdStatus::SEDANG_DIPROSES]);

    //             IlpdApproval::create([
    //                 'ilpd_id'     => $sppd->ilpd->id,
    //                 'approver_id' => null,
    //                 'status'      => IlpdApprovalStatus::SEDANG_DIPROSES,
    //                 'signature'   => null,
    //                 'sla_due_at'  => null,
    //                 'approved_at' => null,
    //             ]);
    //         }
    //     });

    //     return redirect()->route('dashboard')->with('success', 'Dokumen SPPD berhasil disetujui dan SLA ILPD telah diaktifkan.');
    // }
    public function approve(Request $request, $sppdId)
    {
        // dd($request->all());

        $user = Auth::user();

        if (!$user->signature) {
            return back()->with('error', 'Anda belum mengatur TTD di Profil. Silakan unggah TTD terlebih dahulu.');
        }

        // 1. Validasi input jika ada perubahan dari form approval
        $request->validate([
            'durasi' => 'nullable|integer|min:1',
            'tugas'  => 'nullable|string|max:1000',
        ]);

        $sppd = Sppd::with(['ilpd', 'approval', 'dinas'])->findOrFail($sppdId);

        DB::transaction(function () use ($sppd, $user, $request) {
            
            // 2. Siapkan data SPPD yang diperbarui
            $sppdData = [
                'status' => SppdStatus::DISETUJUI,
            ];

            // Update durasi dan tugas jika dikirim dari form
            if ($request->has('durasi')) {
                $sppdData['durasi'] = (int) $request->input('durasi');
            }

            if ($request->has('tugas')) {
                $sppdData['tugas'] = $request->input('tugas');
            }

            // Update data SPPD
            $sppd->update($sppdData);
            
            // Update status Dinas terkait
            if ($sppd->dinas) {
                $sppd->dinas->update([
                    'status' => DinasStatus::SEDANG_DIPROSES
                ]);
            }

            // 3. Catat Riwayat Approval SPPD
            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => $user->id,
                'status'      => SppdApprovalStatus::DISETUJUI,
                'signature'   => $user->signature,
                'sla_due_at'  => $sppd->dinas->overall_sla ?? null,
                'approved_at' => now(),
            ]);

            // 4. Update & Aktifkan SLA ILPD jika ada
            if ($sppd->ilpd) {
                $sppd->ilpd->update([
                    'status' => IlpdStatus::SEDANG_DIPROSES
                ]);

                IlpdApproval::create([
                    'ilpd_id'     => $sppd->ilpd->id,
                    'approver_id' => null,
                    'status'      => IlpdApprovalStatus::SEDANG_DIPROSES,
                    'signature'   => null,
                    'sla_due_at'  => null,
                    'approved_at' => null,
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Dokumen SPPD berhasil disetujui dan SLA ILPD telah diaktifkan.');
    }

    public function reject(Request $request, $id)
    {
        // Validasi alasan penolakan jika pakai Modal
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $sppd = Sppd::findOrFail($id);

        // Update status dan simpan alasan
        $sppd->update([
            'status' => SppdStatus::DITOLAK,
            // 'alasan_penolakan' => $request->alasan_penolakan, // Pastikan kolom ini ada di migration ilpd jika ingin mencatatnya
        ]);

        return redirect()->back()->with('success', 'Pengajuan SPPD berhasil ditolak.');
    }

    public function approvals(Request $request, $sppdId)
    {
        $user = Auth::user();

        if (!$user->signature) {
            return back()->with('error', 'Anda belum mengatur TTD di Profil. Silakan unggah TTD terlebih dahulu.');
        }

        $sppd = Sppd::with(['ilpd', 'approval'])->findOrFail($sppdId);

        DB::transaction(function () use ($sppd, $user) {
            $sppd->update(['status' => 'Disetujui']);
            
            if ($sppd->dinas) {
                $sppd->dinas->update(['status' => 'Sedang Diproses']);
            }

            $lastApproval = SppdApproval::where('sppd_id', $sppd->id)->latest()->first();

            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => $user->id,
                'status'      => 'Disetujui',
                'signature'   => $user->signature,
                'approved_at' => now(),
            ]);

            if ($sppd->ilpd) {
                $sppd->ilpd->update(['status' => 'Sedang Diproses']);

                IlpdApproval::create([
                    'ilpd_id'     => $sppd->ilpd->id,
                    'approver_id' => $user->id,
                    'status'      => 'Sedang Diproses',
                    'signature'   => null,
                    'sla_due_at'  => now()->addHours(24),
                    'approved_at' => now(),
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Dokumen SPPD berhasil disetujui dan SLA ILPD telah diaktifkan.');
    }
}