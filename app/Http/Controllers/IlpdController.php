<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppd;
use App\Models\SppdApproval;
use App\Models\Transport;
use App\Models\Keperluan;
use App\Models\Golongan;
use App\Models\Kota;
use App\Models\Tarif;
use App\Models\Ilpd;
use App\Models\IlpdApproval;
use App\Models\DetailIlpd;
use App\Models\Tiket;
use App\Models\Dinas;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Enums\SppdStatus;
use App\Enums\DinasStatus;
use App\Enums\SppdApprovalStatus;
use App\Enums\IlpdStatus;
use App\Enums\IlpdApprovalStatus;

class IlpdController extends Controller
{
    public function create($sppdId = null)
    {
        $userId = auth()->id();

        if ($sppdId) {
            $sppd = Sppd::with(['user', 'kota'])
            // $sppd = Sppd::with(['user', 'kota', 'unit'])
                    ->where('id', $sppdId)
                    ->where('user_id', $userId)
                    ->where('status', 'Draft')
                    ->first();

            if (!$sppd) {
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak: Form SPPD tidak ditemukan atau sudah diajukan/diproses.');
            }
        } else {
            $sppd = Sppd::with(['user', 'kota'])
            // $sppd = Sppd::with(['user', 'kota', 'units'])
                    ->where('user_id', $userId)
                    ->where('status', 'Draft')
                    ->first();
        }

        if (!$sppd) {
            return redirect()->route('sppd.create')
                ->with('error', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
        }

        $golonganId = $sppd->user->golongan_id ?? null;

        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        $transports = Transport::where('is_active', true)->get(); 
        $keperluans = Keperluan::where('is_active', true)->get();

        return view('ilpd.create', compact('sppd', 'transports', 'keperluans', 'tarif'));
    }

    // public function store(Request $request)
    // {
    //     // try {

    //     $request->validate([
    //         'sppd_id'       => 'required|exists:sppd,id',
    //         'tanggal_awal'  => 'required|date',
    //         'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
    //     ]);

    //     $sppdId = $request->input('sppd_id');
    //     $sppd = Sppd::with(['user', 'kota'])->findOrFail($sppdId);

    //     $golonganId     = $sppd->user->golongan_id ?? null;
    //     $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

    //     $tarif = Tarif::where('golongan_id', $golonganId)
    //         ->where('kota_kategori_id', $kotaKategoriId)
    //         ->first();

    //     $durasi = (int) ($sppd->durasi ?? 1);

    //     // 1. Ambil input Qty (Hari/Malam) dari form dengan batasan minimal 0 & maksimal durasi SPPD
    //     $dinasQty = $durasi; // Dinas selalu full durasi SPPD
    //     $makanQty = min(max((int) $request->input('makan_qty', 0), 0), $durasi);
    //     $hotelQty = min(max((int) $request->input('hotel_qty', 0), 0), $durasi);

    //     // 2. Ambil tarif per hari/malam (bersihkan karakter non-angka)
    //     $dinasPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
    //     $makanPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
    //     $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

    //     // 3. Hitung subtotal masing-masing komponen (Tarif x Qty)
    //     $totalDinas = $dinasPerHari * $dinasQty;
    //     $totalMakan = $makanPerHari * $makanQty;
    //     $totalHotel = $hotelPerMalam * $hotelQty;

    //     // 4. Hitung Total Biaya Harian (Penjumlahan Subtotal)
    //     $totalHarian = $totalDinas + $totalMakan + $totalHotel;

    //     $cleanInt = fn($key) => (int) preg_replace('/[^0-9]/', '', $request->input($key, 0));

    //     // 5. Biaya Opsional (di-clean dulu angkanya)
    //     $bbm            = $cleanInt('bbm');
    //     $transportLokal = $cleanInt('transport_lokal');
    //     $visa           = $cleanInt('visa');
    //     $fiskal         = $cleanInt('fiskal');
    //     $airportTax     = $cleanInt('airport_tax');
    //     $parkirToll     = $cleanInt('parkir_toll');
    //     $entertainment  = $cleanInt('entertainment'); // Typo fixed
    //     $dll            = $cleanInt('dll');

    //     $totalOpsional = $bbm + $transportLokal + $visa + $fiskal + $airportTax + $parkirToll + $entertainment + $dll;

    //     // 6. Grand Total Akhir
    //     $grandTotal = $totalHarian + $totalOpsional;
    //     // $tarif = Tarif::where('golongan_id', $golonganId)
    //     //     ->where('kota_kategori_id', $kotaKategoriId)
    //     //     ->first();

    //     // $durasi = (int) ($sppd->durasi ?? 1);

    //     // $makanPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
    //     // $dinasPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
    //     // $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

    //     // $totalMakan = $makanPerHari * $durasi;
    //     // $totalDinas = $dinasPerHari * $durasi;
    //     // $totalHotel = $hotelPerMalam * $durasi;

    //     // $totalPerHari = $makanPerHari + $dinasPerHari + $hotelPerMalam;
    //     // $grandTotal = $totalPerHari * $durasi;

    //     $dinas = Dinas::findOrFail($sppd->dinas_id);

    //     $slaDueAt =$dinas->overall_sla;

    //     DB::transaction(function () use (
    //         $request, $sppd, $tarif, $makanQty, $hotelQty,
    //         $totalDinas, $totalHotel, $totalMakan, $grandTotal, $slaDueAt,
    //         $bbm, $transportLokal, $visa, $fiskal, $airportTax, $parkirToll, $entertainment, $dll
    //     ) {

    //     // try {

    //         $ilpd = Ilpd::create([
    //             'dinas_id'      => $sppd->dinas_id,
    //             'sppd_id'       => $sppd->id,
    //             'no_ilpd'       => $this->generateNoIlpd(),
    //             'tanggal_awal'  => $request->tanggal_awal,
    //             'tanggal_akhir' => $request->tanggal_akhir,
    //             'status'        => IlpdStatus::MENUNGGU_APPROVAL,
    //         ]);

    //         DetailIlpd::create([
    //             'ilpd_id'         => $ilpd->id,
    //             'tarif_id'        => $tarif->id ?? null,
    //             'dinas'           => $totalDinas,
    //             'makan'           => $totalMakan,
    //             'makan_qty'       => $makanQty,
    //             'hotel'           => $totalHotel,
    //             'hotel_qty'       => $hotelQty,
    //             'laundry'         => 'Actual',
    //             'bbm'             => $bbm,
    //             'transport_lokal' => $transportLokal,
    //             'visa'            => $visa,
    //             'fiskal'          => $fiskal,
    //             'airport_tax'     => $airportTax,
    //             'parkir_toll'     => $parkirToll,
    //             'entertainment'   => $entertainment,
    //             'dll'             => $dll,
    //             'total'           => $grandTotal,
    //             'uang_muka'       => $grandTotal,
    //         ]);

    //         $sppd->update([
    //             'status' => SppdStatus::MENUNGGU_APPROVAL,
    //         ]);

    //         SppdApproval::create([
    //             'sppd_id'     => $sppd->id,
    //             'approver_id' => null,
    //             'status'      => SppdApprovalStatus::MENUNGGU_APPROVAL,
    //             'signature'   => null,
    //             'sla_due_at'  => $slaDueAt,
    //             'approved_at' => null,
    //         ]);

    //         IlpdApproval::create([
    //             'ilpd_id'     => $ilpd->id,
    //             'approver_id' => null,
    //             'status'      => IlpdApprovalStatus::MENUNGGU_APPROVAL,
    //             'signature'   => null,
    //             'sla_due_at'  => $slaDueAt,
    //             'approved_at' => null,
    //         ]);

    //         if ($sppd->dinas_id) {
    //             Dinas::where('id', $sppd->dinas_id)->update([
    //                 'status' => DinasStatus::MENUNGGU_APPROVAL,
    //             ]);
    //         }
    //     });

    //     // } catch (\Exception $e) {
    //     //     dd($e->getMessage()); // Ini akan menampilkan pesan error yang sebenarnya
    //     // }

    //     return redirect('/dashboard')->with('success', 'ILPD berhasil diajukan!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'sppd_id'       => 'required|exists:sppd,id',
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $sppdId = $request->input('sppd_id');
        $sppd   = Sppd::with(['user', 'kota'])->findOrFail($sppdId);

        $golonganId     = $sppd->user->golongan_id ?? null;
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        $durasi = (int) ($sppd->durasi ?? 1);

        // 1. Ambil Qty
        $dinasQty = $durasi;
        $makanQty = min(max((int) $request->input('makan_qty', 0), 0), $durasi);
        $hotelQty = min(max((int) $request->input('hotel_qty', 0), 0), $durasi);

        // 2. Ambil Tarif per Hari/Malam
        $dinasPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
        $makanPerHari  = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
        $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

        // 3. Hitung Subtotal
        $totalDinas = $dinasPerHari * $dinasQty;
        $totalMakan = $makanPerHari * $makanQty;
        $totalHotel = $hotelPerMalam * $hotelQty;

        // 4. Hitung Total Harian
        $totalHarian = $totalDinas + $totalMakan + $totalHotel;

        $cleanInt = fn($key) => (int) preg_replace('/[^0-9]/', '', $request->input($key, 0));

        // 5. Biaya Opsional
        $bbm            = $cleanInt('bbm');
        $transportLokal = $cleanInt('transport_lokal');
        $visa           = $cleanInt('visa');
        $fiskal         = $cleanInt('fiskal');
        $airportTax     = $cleanInt('airport_tax');
        $parkirToll     = $cleanInt('parkir_toll');
        $entertainment  = $cleanInt('entertainment');
        $dll            = $cleanInt('dll');

        $totalOpsional = $bbm + $transportLokal + $visa + $fiskal + $airportTax + $parkirToll + $entertainment + $dll;

        // 6. Grand Total
        $grandTotal = $totalHarian + $totalOpsional;

        $dinas = Dinas::findOrFail($sppd->dinas_id);
        $slaDueAt = $dinas->overall_sla;

        // DB::transaction(function () use (
        //     $request, $sppd, $tarif, $makanQty, $hotelQty,
        //     $totalDinas, $totalHotel, $totalMakan, $grandTotal, $slaDueAt,
        //     $bbm, $transportLokal, $visa, $fiskal, $airportTax, $parkirToll, $entertainment, $dll
        // ) {
        DB::transaction(function () use (
            $request, $sppd, $tarif, $makanQty, $hotelQty,
            $totalDinas, $hotelPerMalam, $makanPerHari, $grandTotal, $slaDueAt,
            $bbm, $transportLokal, $visa, $fiskal, $airportTax, $parkirToll, $entertainment, $dll
        ) {
            $ilpd = Ilpd::create([
                'dinas_id'      => $sppd->dinas_id,
                'sppd_id'       => $sppd->id,
                'no_ilpd'       => $this->generateNoIlpd(),
                'tanggal_awal'  => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status'        => IlpdStatus::MENUNGGU_APPROVAL,
            ]);

            DetailIlpd::create([
                'ilpd_id'         => $ilpd->id,
                'tarif_id'        => $tarif?->id,
                'dinas'           => $totalDinas,
                'makan'           => $makanPerHari,
                // 'makan'           => $totalMakan,
                'makan_qty'       => $makanQty,
                'hotel'           => $hotelPerMalam,
                // 'hotel'           => $totalHotel,
                'hotel_qty'       => $hotelQty,
                'bbm'             => $bbm,
                'transport_lokal' => $transportLokal,
                'visa'            => $visa,
                'fiskal'          => $fiskal,
                'airport_tax'     => $airportTax,
                'laundry'         => 'Actual',
                'parkir_toll'     => $parkirToll,
                'entertainment'   => $entertainment,
                'dll'             => $dll,
                'total'           => $grandTotal,
                'uang_muka'       => $grandTotal,
            ]);

            $sppd->update([
                'status' => SppdStatus::MENUNGGU_APPROVAL,
            ]);

            SppdApproval::create([
                'sppd_id'     => $sppd->id,
                'approver_id' => null,
                'status'      => SppdApprovalStatus::MENUNGGU_APPROVAL,
                'signature'   => null,
                'sla_due_at'  => $slaDueAt,
                'approved_at' => null,
            ]);

            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => null,
                'status'      => IlpdApprovalStatus::MENUNGGU_APPROVAL,
                'signature'   => null,
                'sla_due_at'  => $slaDueAt,
                'approved_at' => null,
            ]);

            if ($sppd->dinas_id) {
                Dinas::where('id', $sppd->dinas_id)->update([
                    'status' => DinasStatus::MENUNGGU_APPROVAL,
                ]);
            }
        });

        return redirect('/dashboard')->with('success', 'ILPD berhasil diajukan!');
    }

    private function generateNoIlpd()
    {
        $prefix = 'ILPD/' . date('Y/m') . '/';
        $lastIlpd = Ilpd::where('no_ilpd', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastIlpd) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastIlpd->no_ilpd, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // public function edit($id)
    // public function edit($sppdId = null)
    // {
    //     // $ilpd = Ilpd::findOrFail($id);

    //     // $users      = User::select('id', 'name', 'nik')->get();
    //     // $kotas      = Kota::all();
    //     // $keperluans = Keperluan::all();
    //     // $transports = Transport::all();
    //     $userId = auth()->id();

    //     if ($sppdId) {
    //         $sppd = Sppd::with(['user', 'kota'])
    //                 ->where('id', $sppdId)
    //                 ->where('user_id', $userId)
    //                 ->where('status', 'Draft')
    //                 ->first();

    //         if (!$sppd) {
    //             return redirect()->route('dashboard')
    //                 ->with('error', 'Akses ditolak: Form SPPD tidak ditemukan atau sudah diajukan/diproses.');
    //         }
    //     } else {
    //         $sppd = Sppd::with(['user', 'kota'])
    //                 ->where('user_id', $userId)
    //                 ->where('status', 'Draft')
    //                 ->first();
    //     }

    //     if (!$sppd) {
    //         return redirect()->route('sppd.create')
    //             ->with('error', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
    //     }

    //     $golonganId = $sppd->user->golongan_id ?? null;

    //     $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

    //     $tarif = Tarif::where('golongan_id', $golonganId)
    //         ->where('kota_kategori_id', $kotaKategoriId)
    //         ->first();

    //     $transports = Transport::where('is_active', true)->get(); 
    //     $keperluans = Keperluan::where('is_active', true)->get();

    //     return view('ilpd.edit', compact('sppd', 'transports', 'keperluans', 'tarif'));
    //     // return view('ilpd.edit', compact('ilpd', 'users', 'kotas', 'keperluans', 'transports'));
    // }
    public function edit($id)
    {
        // 1. Ambil data ILPD beserta relasi SPPD & Detail Tarif-nya
        $ilpd = Ilpd::with(['sppd.user', 'sppd.kota', 'detail_ilpd.tarif'])->findOrFail($id);
        $sppd = $ilpd->sppd;

        if (!$sppd) {
            return redirect()->route('dashboard')->with('error', 'Data SPPD terkait tidak ditemukan.');
        }

        // 2. Ambil tarif berdasarkan relasi atau master tarif
        $golonganId     = $sppd->user->golongan_id ?? null;
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $tarif = $ilpd->detail_ilpd?->tarif ?? Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        $transports = Transport::where('is_active', true)->get(); 
        $keperluans = Keperluan::where('is_active', true)->get();

        return view('ilpd.edit', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarif'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Input Utama
        $request->validate([
            'tanggal_awal'      => 'required|date',
            'transport_id'      => 'nullable|array',
            'keperluan_id'      => 'nullable|array', 
            'transport_lainnya' => 'nullable|string',
            'keperluan_lainnya' => 'nullable|string',
            'tiket_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Qty Validation
            'makan_qty'         => 'nullable|integer|min:0',
            'hotel_qty'         => 'nullable|integer|min:0',

            // Opsional & Tarif
            'uang_dinas'        => 'nullable',
            'uang_makan'        => 'nullable',
            'uang_hotel'        => 'nullable',
            'bbm'               => 'nullable',
            'transport_lokal'   => 'nullable',
            'visa'              => 'nullable',
            'fiskal'            => 'nullable',
            'airport_tax'       => 'nullable',
            'parkir_toll'       => 'nullable',
            'entertainment'     => 'nullable',
            'dll'               => 'nullable',
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            $ilpd = Ilpd::with(['sppd', 'detail_ilpd.tarif'])->findOrFail($id);
            $sppd = $ilpd->sppd;

            // 2. Helper untuk konversi format Rupiah ke Angka Murni
            $parseRupiah = fn($val) => (float) preg_replace('/[^0-9]/', '', $val ?? 0);

            // 3. Tanggal & Durasi
            $tanggalAwalBaru = \Carbon\Carbon::parse($request->tanggal_awal);
            $durasiHari = (int) ($sppd->durasi ?? 1);
            $tanggalAkhirBaru = $tanggalAwalBaru->copy()->addDays($durasiHari - 1);

            $ilpd->update([
                'tanggal_awal'  => $tanggalAwalBaru->format('Y-m-d'),
                'tanggal_akhir' => $tanggalAkhirBaru->format('Y-m-d'),
                'status'        => IlpdStatus::DISETUJUI,
            ]);

            if ($sppd) {
                $sppd->update([
                    'transport_id'      => $request->transport_id,
                    'keperluan_id'      => $request->keperluan_id,
                    'transport_lainnya' => $request->transport_lainnya,
                    'keperluan_lainnya' => $request->keperluan_lainnya,
                ]);
            }

            // 4. File Tiket
            if ($request->hasFile('tiket_file')) {
                $file = $request->file('tiket_file');
                $filePath = $file->store('tikets', 'public');

                Tiket::create([
                    'ilpd_id'     => $ilpd->id,
                    'type'        => 'jalan',
                    'file'        => $filePath,
                    'uploaded_by' => $user->id,
                    'uploaded_at' => now(),
                ]);
            }

            // 5. Update Status Dinas
            if ($ilpd->dinas_id) {
                $dinas = Dinas::find($ilpd->dinas_id);
                if ($dinas) {
                    $statusSlaFinal = now()->lessThanOrEqualTo($dinas->overall_sla) ? 'ON_TIME' : 'LATE';

                    $dinas->update([
                        'status'     => DinasStatus::DISETUJUI,
                        'status_sla' => $statusSlaFinal,
                    ]);
                }
            }

            // 6. PERHITUNGAN ULANG BE (RE-CALCULATION & VALIDASI QTY)
            $makanQty = min((int) ($request->makan_qty ?? 1), $durasiHari); // Batasi maksimal durasi SPPD
            $hotelQty = min((int) ($request->hotel_qty ?? 1), $durasiHari); // Batasi maksimal durasi SPPD

            // Ambil nilai rate (Prioritas: Request input -> Master Tarif)
            $dinasRate = $request->filled('uang_dinas') ? $parseRupiah($request->uang_dinas) : ($ilpd->detail_ilpd->tarif->dinas ?? 0);
            $makanRate = $request->filled('uang_makan') ? $parseRupiah($request->uang_makan) : ($ilpd->detail_ilpd->tarif->makan ?? 0);
            $hotelRate = $request->filled('uang_hotel') ? $parseRupiah($request->uang_hotel) : ($ilpd->detail_ilpd->tarif->hotel ?? 0);

            // Subtotal Perhitungan BE
            $subtotalDinas = $dinasRate * $durasiHari;
            $subtotalMakan = $makanRate * $makanQty;
            $subtotalHotel = $hotelRate * $hotelQty;

            // Biaya Opsional (Parsial Rupiah)
            $bbm            = $parseRupiah($request->bbm);
            $transportLokal = $parseRupiah($request->transport_lokal);
            $visa           = $parseRupiah($request->visa);
            $fiskal         = $parseRupiah($request->fiskal);
            $airportTax     = $parseRupiah($request->airport_tax);
            $parkirToll     = $parseRupiah($request->parkir_toll);
            $entertainment  = $parseRupiah($request->entertainment);
            $dll            = $parseRupiah($request->dll);

            // Grand Total Akhir
            $grandTotal = $subtotalDinas + $subtotalMakan + $subtotalHotel + 
                        $bbm + $transportLokal + $visa + $fiskal + 
                        $airportTax + $parkirToll + $entertainment + $dll;

            // 7. Save ke Detail ILPD
            DetailIlpd::updateOrCreate(
                ['ilpd_id' => $ilpd->id],
                [
                    'tarif_id'        => $request->tarif_id ?? $ilpd->detail_ilpd?->tarif_id,
                    'dinas'           => $dinasRate,
                    'makan'           => $makanRate,
                    'makan_qty'       => $makanQty,
                    'hotel'           => $hotelRate,
                    'hotel_qty'       => $hotelQty,
                    'bbm'             => $bbm,
                    'transport_lokal' => $transportLokal,
                    'visa'            => $visa,
                    'fiskal'          => $fiskal,
                    'airport_tax'     => $airportTax,
                    'parkir_toll'     => $parkirToll,
                    'entertainment'   => $entertainment,
                    'dll'             => $dll,
                    'total'           => $grandTotal, // Simpan total biaya hasil hitung ulang BE
                    'uang_muka'       => $grandTotal, // Simpan total biaya hasil hitung ulang BE
                ]
            );

            // 8. Approval History Log
            $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => $user->id,
                'status'      => IlpdApprovalStatus::DISETUJUI,
                'signature'   => $user->signature,
                'sla_due_at'  => $lastApproval?->sla_due_at ?? $ilpd->dinas?->overall_sla,
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan data diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $ilpd = Ilpd::with([
            'dinas',
            'sppd.kota',
            'sppd.user.golongan',
            'detail_ilpd',
        ])
        ->where('status', IlpdStatus::SEDANG_DIPROSES)
        ->findOrFail($id);

        $sppd = $ilpd->sppd;

        $kotaKategoriId = $ilpd->sppd->kota->kota_kategori_id ?? null;

        $golonganId = $ilpd->sppd->user->golongan_id ?? null;

        $transports = Transport::all(); 
        $keperluans = Keperluan::all();

        $tarifs = Tarif::with('golongan')
            ->where('kota_kategori_id', $kotaKategoriId)
            ->get();

        return view('ilpd.approve', compact('ilpd', 'sppd', 'transports', 'keperluans', 'tarifs', 'golonganId'));
    }

    // public function approve(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     if (!$user->signature) {
    //         return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
    //     }

    //     $request->validate([
    //         'tanggal_awal'      => 'required|date',
    //         'transport_id'      => 'nullable|array',
    //         'keperluan_id'      => 'nullable|array', 
    //         'transport_lainnya' => 'nullable|string',
    //         'keperluan_lainnya' => 'nullable|string',
    //         'tiket_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    //         'bbm'               => 'nullable|numeric',
    //         'transport_lokal'   => 'nullable|numeric',
    //         'visa'              => 'nullable|numeric',
    //         'fiskal'            => 'nullable|numeric',
    //         'airport_tax'       => 'nullable|numeric',
    //         'parkir_toll'       => 'nullable|numeric',
    //         'entertainment'     => 'nullable|numeric',
    //         'dll'               => 'nullable|numeric',
    //     ]);

    //     DB::beginTransaction();
    //     $filePath = null;

    //     try {
    //         $ilpd = Ilpd::with('sppd')->findOrFail($id);
    //         $sppd = $ilpd->sppd;

    //         $tanggalAwalBaru = \Carbon\Carbon::parse($request->tanggal_awal);
    //         $durasiHari = (int) ($sppd->durasi ?? 1);
            
    //         $tanggalAkhirBaru = $tanggalAwalBaru->copy()->addDays($durasiHari - 1);

    //         $ilpd->update([
    //             'tanggal_awal'  => $tanggalAwalBaru->format('Y-m-d'),
    //             'tanggal_akhir' => $tanggalAkhirBaru->format('Y-m-d'),
    //             'status'        => IlpdStatus::DISETUJUI,
    //         ]);

    //         if ($sppd) {
    //             $sppd->update([
    //                 'transport_id' => $request->transport_id,
    //                 'keperluan_id' => $request->keperluan_id,
    //                 'transport_lainnya' => $request->transport_lainnya,
    //                 'keperluan_lainnya' => $request->keperluan_lainnya,
    //             ]);
    //         }

    //         if ($request->hasFile('tiket_file')) {
    //             $file = $request->file('tiket_file');
    //             $filePath = $file->store('tikets', 'public');

    //             Tiket::create([
    //                 'ilpd_id'     => $ilpd->id,
    //                 'type'        => 'jalan',
    //                 'file'        => $filePath,
    //                 'uploaded_by' => $user->id,
    //                 'uploaded_at' => now(),
    //             ]);
    //         }

    //         if ($ilpd->dinas_id) {
    //             $dinas = Dinas::find($ilpd->dinas_id);
                
    //             if ($dinas) {
    //                 $statusSlaFinal = now()->lessThanOrEqualTo($dinas->overall_sla) ? 'ON_TIME' : 'LATE';

    //                 $dinas->update([
    //                     'status'     => DinasStatus::DISETUJUI,
    //                     'status_sla' => $statusSlaFinal,
    //                 ]);
    //             }
    //         }

    //         $dataToUpdate = array_filter([
    //             'tarif_id'        => $request->tarif_id,
    //             'dinas'           => $request->dinas,
    //             'makan'           => $request->makan,
    //             'makan_qty'       => $request->makan_qty,
    //             'hotel'           => $request->hotel,
    //             'hotel_qty'       => $request->hotel_qty,
    //             'bbm'             => $request->bbm,
    //             'transport_lokal' => $request->transport_lokal,
    //             'visa'            => $request->visa,
    //             'fiskal'          => $request->fiskal,
    //             'airport_tax'     => $request->airport_tax,
    //             'parkir&toll'     => $request->parkir_toll,
    //             'entertainment'   => $request->entertainment,
    //             'dll'             => $request->dll,
    //         ], function ($value) {
    //             return !is_null($value);
    //         });

    //         if (!empty($dataToUpdate)) {
    //             DetailIlpd::updateOrCreate(
    //                 ['ilpd_id' => $ilpd->id],
    //                 $dataToUpdate
    //             );
    //         }

    //         $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

    //         IlpdApproval::create([
    //             'ilpd_id'     => $ilpd->id,
    //             'approver_id' => $user->id,
    //             'status'      => IlpdApproval::DISETUJUI,
    //             'signature'   => $user->signature,
    //             'sla_due_at'  => $lastApproval?->sla_due_at ?? $ilpd->dinas?->overall_sla,
    //             'approved_at' => now(),
    //         ]);
    //         // }

    //         DB::commit();

    //         return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan data diperbarui.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         if ($filePath && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
    //     }
    // }
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->signature) {
            return redirect()->back()->with('error', 'Gagal: Anda belum mengatur tanda tangan di profil!');
        }

        // 1. Validasi Input Utama
        $request->validate([
            'tanggal_awal'      => 'required|date',
            'transport_id'      => 'nullable|array',
            'keperluan_id'      => 'nullable|array', 
            'transport_lainnya' => 'nullable|string',
            'keperluan_lainnya' => 'nullable|string',
            'tiket_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            
            // Qty Validation
            'makan_qty'         => 'nullable|integer|min:0',
            'hotel_qty'         => 'nullable|integer|min:0',

            // Opsional & Tarif
            'uang_dinas'        => 'nullable',
            'uang_makan'        => 'nullable',
            'uang_hotel'        => 'nullable',
            'bbm'               => 'nullable',
            'transport_lokal'   => 'nullable',
            'visa'              => 'nullable',
            'fiskal'            => 'nullable',
            'airport_tax'       => 'nullable',
            'parkir_toll'       => 'nullable',
            'entertainment'     => 'nullable',
            'dll'               => 'nullable',
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            $ilpd = Ilpd::with(['sppd', 'detail_ilpd.tarif'])->findOrFail($id);
            $sppd = $ilpd->sppd;

            // 2. Helper untuk konversi format Rupiah ke Angka Murni
            $parseRupiah = fn($val) => (float) preg_replace('/[^0-9]/', '', $val ?? 0);

            // 3. Tanggal & Durasi
            $tanggalAwalBaru = \Carbon\Carbon::parse($request->tanggal_awal);
            $durasiHari = (int) ($sppd->durasi ?? 1);
            $tanggalAkhirBaru = $tanggalAwalBaru->copy()->addDays($durasiHari - 1);

            $ilpd->update([
                'tanggal_awal'  => $tanggalAwalBaru->format('Y-m-d'),
                'tanggal_akhir' => $tanggalAkhirBaru->format('Y-m-d'),
                'status'        => IlpdStatus::DISETUJUI,
            ]);

            if ($sppd) {
                $sppd->update([
                    'transport_id'      => $request->transport_id,
                    'keperluan_id'      => $request->keperluan_id,
                    'transport_lainnya' => $request->transport_lainnya,
                    'keperluan_lainnya' => $request->keperluan_lainnya,
                ]);
            }

            // 4. File Tiket
            if ($request->hasFile('tiket_file')) {
                $file = $request->file('tiket_file');
                $filePath = $file->store('tikets', 'public');

                Tiket::create([
                    'ilpd_id'     => $ilpd->id,
                    'type'        => 'jalan',
                    'file'        => $filePath,
                    'uploaded_by' => $user->id,
                    'uploaded_at' => now(),
                ]);
            }

            // 5. Update Status Dinas
            if ($ilpd->dinas_id) {
                $dinas = Dinas::find($ilpd->dinas_id);
                if ($dinas) {
                    $statusSlaFinal = now()->lessThanOrEqualTo($dinas->overall_sla) ? 'ON_TIME' : 'LATE';

                    $dinas->update([
                        'status'     => DinasStatus::DISETUJUI,
                        'status_sla' => $statusSlaFinal,
                    ]);
                }
            }

            // 6. PERHITUNGAN ULANG BE (RE-CALCULATION & VALIDASI QTY)
            $makanQty = min((int) ($request->makan_qty ?? 1), $durasiHari); // Batasi maksimal durasi SPPD
            $hotelQty = min((int) ($request->hotel_qty ?? 1), $durasiHari); // Batasi maksimal durasi SPPD

            // Ambil nilai rate (Prioritas: Request input -> Master Tarif)
            $dinasRate = $request->filled('uang_dinas') ? $parseRupiah($request->uang_dinas) : ($ilpd->detail_ilpd->tarif->dinas ?? 0);
            $makanRate = $request->filled('uang_makan') ? $parseRupiah($request->uang_makan) : ($ilpd->detail_ilpd->tarif->makan ?? 0);
            $hotelRate = $request->filled('uang_hotel') ? $parseRupiah($request->uang_hotel) : ($ilpd->detail_ilpd->tarif->hotel ?? 0);

            // Subtotal Perhitungan BE
            $subtotalDinas = $dinasRate * $durasiHari;
            $subtotalMakan = $makanRate * $makanQty;
            $subtotalHotel = $hotelRate * $hotelQty;

            // Biaya Opsional (Parsial Rupiah)
            $bbm            = $parseRupiah($request->bbm);
            $transportLokal = $parseRupiah($request->transport_lokal);
            $visa           = $parseRupiah($request->visa);
            $fiskal         = $parseRupiah($request->fiskal);
            $airportTax     = $parseRupiah($request->airport_tax);
            $parkirToll     = $parseRupiah($request->parkir_toll);
            $entertainment  = $parseRupiah($request->entertainment);
            $dll            = $parseRupiah($request->dll);

            // Grand Total Akhir
            $grandTotal = $subtotalDinas + $subtotalMakan + $subtotalHotel + 
                        $bbm + $transportLokal + $visa + $fiskal + 
                        $airportTax + $parkirToll + $entertainment + $dll;

            // 7. Save ke Detail ILPD
            DetailIlpd::updateOrCreate(
                ['ilpd_id' => $ilpd->id],
                [
                    'tarif_id'        => $request->tarif_id ?? $ilpd->detail_ilpd?->tarif_id,
                    'dinas'           => $dinasRate,
                    'makan'           => $makanRate,
                    'makan_qty'       => $makanQty,
                    'hotel'           => $hotelRate,
                    'hotel_qty'       => $hotelQty,
                    'bbm'             => $bbm,
                    'transport_lokal' => $transportLokal,
                    'visa'            => $visa,
                    'fiskal'          => $fiskal,
                    'airport_tax'     => $airportTax,
                    'laundry'         => 'Actual',
                    'parkir_toll'     => $parkirToll,
                    'entertainment'   => $entertainment,
                    'dll'             => $dll,
                    'total'           => $grandTotal, // Simpan total biaya hasil hitung ulang BE
                    'uang_muka'       => $grandTotal, // Simpan total biaya hasil hitung ulang BE
                ]
            );

            // 8. Approval History Log
            $lastApproval = IlpdApproval::where('ilpd_id', $ilpd->id)->latest()->first();

            IlpdApproval::create([
                'ilpd_id'     => $ilpd->id,
                'approver_id' => $user->id,
                'status'      => IlpdApprovalStatus::DISETUJUI,
                'signature'   => $user->signature,
                'sla_due_at'  => $lastApproval?->sla_due_at ?? $ilpd->dinas?->overall_sla,
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'ILPD berhasil disetujui dan data diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyetujui ILPD: ' . $e->getMessage());
        }
    }

    public function laporan($id)
    {
        $ilpd = Ilpd::with(['sppd.kota', 'detail_ilpd', 'laporan', 'sppd.user'])
                    ->findOrFail($id);

        $laporan = $ilpd->laporan ?? new Laporan();

        return view('ilpd.laporan', compact('ilpd', 'laporan'));
    }

    public function report(Request $request, $id)
    {
        $ilpd = Ilpd::with('detail_ilpd')->findOrFail($id);

        $validated = $request->validate([
            'realisasi'       => 'required|array',
            'uang_muka'       => 'nullable|numeric|min:0',
            'total_realisasi' => 'required|numeric|min:0',
            'total_dibayar'   => 'required|numeric|min:0',
            'selisih'         => 'required|numeric',
            'keterangan'      => 'nullable|string',
            'laporan_1'       => 'nullable|string',
            'laporan_2'       => 'nullable|string',
        ]);

        $totalPerkiraan = $ilpd->detail_ilpd ? $ilpd->detail_ilpd->sum('uang_muka') : 0;

        $uangMuka = (float) $request->input('uang_muka', 0);
        $totalRealisasi = (float) $request->input('total_realisasi', 0);
        
        $selisihBE = $uangMuka - $totalRealisasi;

        DB::transaction(function () use ($request, $ilpd, $totalPerkiraan) {
        
            Laporan::updateOrCreate(
                ['ilpd_id' => $ilpd->id],
                [
                    'realisasi'       => $request->input('realisasi'),
                    'perkiraan'       => $totalPerkiraan,
                    'uang_muka'       => $request->input('uang_muka', 0),
                    'total_realisasi' => $request->input('total_realisasi', 0),
                    'total_dibayar'   => $request->input('total_dibayar', 0),
                    'selisih'         => $selisihBE,
                    'keterangan'      => $request->input('keterangan'),
                    'laporan_1'       => $request->input('laporan_1'),
                    'laporan_2'       => $request->input('laporan_2'),
                ]
            );

            if ($ilpd->dinas) {
                $ilpd->dinas->update([
                    'status' => 'Selesai'
                ]);
            } 
            else {
                Dinas::where('ilpd_id', $ilpd->id)->update([
                    'status' => 'Selesai'
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Laporan Perjalanan Dinas berhasil disimpan.');
    }
}