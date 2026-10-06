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
            
            $table->foreignId('ilpd_id')
                  ->constrained('ilpd')
                  ->onDelete('cascade');

            $table->json('realisasi')->nullable();

            $table->decimal('perkiraan', 15, 2)->default(0);
            $table->decimal('uang_muka', 15, 2)->default(0);
            $table->decimal('total_realisasi', 15, 2)->default(0);
            $table->decimal('total_dibayar', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0);
            $table->text('keterangan')->nullable();

            $table->text('laporan_1')->nullable();
            $table->text('laporan_2')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};