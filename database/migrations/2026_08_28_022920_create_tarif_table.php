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
        Schema::create('tarif', function (Blueprint $table) {
            $table->id();

            $table->foreignId('golongan_id')
                ->constrained('golongan')
                ->restrictOnDelete();

            $table->foreignId('kota_kategori_id')
                ->constrained('kota_kategori')
                ->restrictOnDelete();

            // $table->string('travel_scope');

            $table->decimal('makan', 15, 2);
            $table->decimal('dinas', 15, 2);
            $table->decimal('hotel', 15, 2);

            $table->timestamps();

            $table->unique([
                'golongan_id',
                'kota_kategori_id',
                // 'travel_scope'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif');
    }
};
