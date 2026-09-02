<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ilpd', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dinas_id')
                ->constrained('dinas')
                ->cascadeOnDelete();

            $table->foreignId('sppd_id')
                ->unique()
                ->constrained('sppd')
                ->cascadeOnDelete();

            $table->string('no_ilpd')->unique();

            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('status')->default('Menunggu Approval');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ilpd');
    }
};
