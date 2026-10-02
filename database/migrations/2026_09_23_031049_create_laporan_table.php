<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel ilpd (Parent LPJ)
            $table->foreignId('ilpd_id')
                  ->constrained('ilpd')
                  ->onDelete('cascade');

            // Menampung 12+ komponen biaya realisasi (makan, hotel, bbm, parkir, dll)
            $table->json('realisasi')->nullable();

            // Ringkasan Keuangan
            $table->decimal('perkiraan', 15, 2)->default(0);
            $table->decimal('uang_muka', 15, 2)->default(0);
            $table->decimal('total_realisasi', 15, 2)->default(0);
            $table->decimal('total_dibayar', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0); // Positif (Kurang) / Negatif (Lebih)
            $table->text('keterangan')->nullable();

            // Laporan Teks Kegiatan
            $table->text('laporan_1')->nullable();
            $table->text('laporan_2')->nullable();

            // Status Laporan Dinas
            // $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};