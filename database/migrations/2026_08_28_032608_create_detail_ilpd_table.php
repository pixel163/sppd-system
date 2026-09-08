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
        Schema::create('detail_ilpd', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ilpd_id')
                ->constrained('ilpd')
                ->cascadeOnDelete();

            $table->foreignId('tarif_id')
                ->constrained('tarif')
                ->restrictOnDelete();

            $table->decimal('makan', 15, 2)->default(0);
            $table->decimal('dinas', 15, 2)->default(0);
            $table->decimal('hotel', 15, 2)->default(0);

            $table->text('laundry')->default('Actual');
            $table->decimal('bbm', 15, 2)->nullable();
            $table->decimal('transport_lokal', 15, 2)->nullable();
            $table->decimal('visa', 15, 2)->nullable();
            $table->decimal('fiskal', 15, 2)->nullable();
            $table->decimal('airport_tax', 15, 2)->nullable();
            $table->decimal('parkir&toll', 15, 2)->nullable();
            $table->decimal('entertaiment', 15, 2)->nullable();
            $table->decimal('dll', 15, 2)->nullable();

            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('uang_muka', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_ilpd');
    }
};
