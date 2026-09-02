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
            Schema::create('sppd', function (Blueprint $table) {
                $table->id();

                $table->foreignId('dinas_id')
                    ->constrained('dinas')
                    ->cascadeOnDelete();

                $table->string('no_sppd')->unique();

                $table->foreignId('user_id')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->foreignId('kota_id')
                    ->constrained('kota')
                    ->restrictOnDelete();

                $table->foreignId('keperluan_id')
                    ->constrained('keperluan')
                    ->restrictOnDelete();

                $table->foreignId('transport_id')
                    ->constrained('transport')
                    ->restrictOnDelete();

                $table->unsignedInteger('durasi');

                $table->text('tugas');
                $table->string('status')->default('Menunggu Approval');

                $table->timestamps();
            });
        }

    public function down(): void
    {
        Schema::dropIfExists('sppd');
    }
};
