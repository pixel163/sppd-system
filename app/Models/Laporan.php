<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'ilpd_id',
        'realisasi',
        'perkiraan',
        'uang_muka',
        'total_realisasi',
        'total_dibayar',
        'selisih',
        'keterangan',
        'laporan_1',
        'laporan_2',
        // 'status',
    ];

    /**
     * Konversi otomatis kolom JSON menjadi Array PHP saat dipanggil
     */
    protected $casts = [
        'realisasi'        => 'array',
        'perkiraan'        => 'decimal:2',
        'uang_muka'        => 'decimal:2',
        'total_realisasi'  => 'decimal:2',
        'total_dibayar'    => 'decimal:2',
        'selisih'          => 'decimal:2',
    ];

    /**
     * Relasi ke Model ILPD
     */
    public function ilpd()
    {
        return $this->belongsTo(Ilpd::class, 'ilpd_id');
    }

    // public function getRealisasiListAttribute()
    // {
    //     // Decode JSON string ke array PHP
    //     $rawIds = $this->realisasi;
    //     $ids = is_string($rawIds) ? (json_decode($rawIds, true) ?? []) : ($rawIds ?? []);
    //     $ids = is_array($ids) ? $ids : [$ids];

    //     // Ambil nama dari tabel master Laporan
    //     $names = \App\Models\Laporan::whereIn('id', $ids)->pluck('name')->toArray();

    //     return !empty($names) ? implode(', ', $names) : '-';
    // }
}