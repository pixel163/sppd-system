<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use App\Models\Transport;
use App\Models\Keperluan;
use App\Models\Kota;
use App\Models\Tarif;
use App\Models\Ilpd;
use App\Models\DetailIlpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IlpdController extends Controller
{
    public function create(Sppd $sppd)
    {
        $sppd = Sppd::where('user_id', auth()->id())
                ->latest()
                ->first();

        // Jika user sama sekali belum pernah buat Form 1 (SPPD)
        if (!$sppd) {
            return redirect()->route('sppd.create')
                ->with('warning', 'Anda harus mengisi Form SPPD terlebih dahulu sebelum membuat Perizinan.');
        }
        
        // / 1. Ambil Golongan User dari relasi SPPD -> User -> Golongan
        // (Sesuaikan nama relasi/kolom di project kamu, misal: $sppd->user->golongan_id)
        $golonganId = $sppd->user->golongan_id ?? null;

        // 2. Ambil Kategori Kota dari relasi SPPD -> Kota -> Kategori
        // (Sesuaikan nama relasi/kolom, misal: $sppd->kota->kota_kategori_id)
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        // 3. Cari tarif yang cocok di tabel 'tarif'
        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        $transports = Transport::all(); 
        $keperluans = Keperluan::all(); 

        return view('ilpd.create', compact('sppd', 'transports', 'keperluans', 'tarif'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'sppd_id'       => 'required|exists:sppd,id',
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // 2. Ambil Data SPPD Terkait
        $sppdId = $request->input('sppd_id');
        $sppd = Sppd::with(['user', 'kota'])->findOrFail($sppdId);

        // 3. Ambil Tarif Berdasarkan Golongan User & Kategori Kota
        $golonganId     = $sppd->user->golongan_id ?? null;
        $kotaKategoriId = $sppd->kota->kota_kategori_id ?? null;

        $tarif = Tarif::where('golongan_id', $golonganId)
            ->where('kota_kategori_id', $kotaKategoriId)
            ->first();

        // 4. Bersihkan/Parse Nominal Rupiah dari Input Form (Hapus Titik)
        $makanPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_makan', $tarif->makan ?? 0));
        $dinasPerHari = (int) preg_replace('/[^0-9]/', '', $request->input('uang_dinas', $tarif->dinas ?? 0));
        $hotelPerMalam = (int) preg_replace('/[^0-9]/', '', $request->input('uang_hotel', $tarif->hotel ?? 0));

        // 5. Kalkulasi Total Biaya
        $durasiHari = (int) ($sppd->durasi ?? 1);
        $totalPerHari = $makanPerHari + $dinasPerHari + $hotelPerMalam;
        $grandTotal = $totalPerHari * $durasiHari;

        // 6. Eksekusi Simpan dengan Database Transaction
        DB::transaction(function () use ($request, $sppd, $tarif, $makanPerHari, $dinasPerHari, $hotelPerMalam, $grandTotal) {
            
            // A. Simpan ke Tabel `ilpd`
            $ilpd = Ilpd::create([
                'dinas_id'      => $sppd->dinas_id, // Mengambil dinas_id dari SPPD
                'sppd_id'       => $sppd->id,
                'no_ilpd'       => $this->generateNoIlpd(), // Panggil helper generator
                'tanggal_awal'  => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status'        => 'menunggu_approval',
            ]);

            // B. Simpan ke Tabel `detail_ilpd`
            DetailIlpd::create([
                'ilpd_id'  => $ilpd->id,
                'tarif_id' => $tarif->id ?? null,
                'makan'    => $makanPerHari,
                'dinas'    => $dinasPerHari,
                'hotel'    => $hotelPerMalam,
                'bbm'      => null,
                'transport_lokal' => null,
                'visa'     => null,
                'fiskal'   => null,
                'airpot_tax' => null,
                'parkir&toll' => null,
                'entertaiment' => null,
                'dll'      => null,
                'total'    => $grandTotal,
            ]);
        });

        // return redirect()->route('ilpd.index')->with('success', 'ILPD berhasil diajukan!');
        return redirect('/dashboard')->with('success', 'ILPD berhasil diajukan!');
    }

    /**
     * Helper Function untuk Generate No ILPD Otomatis
     * Contoh Format: ILPD/2026/09/0001
     */
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
}