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
        Schema::create('sppd_approval', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sppd_id')
                ->nullable()
                ->constrained('sppd')
                ->cascadeOnDelete();

            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // Gunakan string biasa (misal: 'PENDING', 'APPROVED', 'REJECTED')
            // $table->string('status')->default('PENDING');
            $table->string('status');

            // Menyimpan urutan/tingkat approval jika multi-stage (opsional)
            // $table->string('stage')->nullable(); // contoh: 'MANAGER', 'GA', 'FINANCE'

            $table->string('signature')->nullable();

            // Waktu batas akhir/deadline SLA
            $table->timestamp('sla_due_at')->nullable();

            // Waktu aktual ketika approver mengeksekusi (Approve/Reject)
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }
    // public function up(): void
    // {
    //     Schema::create('sppd_approval', function (Blueprint $table) {
    //         $table->id();

    //         $table->foreignId('sppd_id')
    //             ->nullable()
    //             ->constrained('sppd')
    //             ->cascadeOnDelete();

    //         // $table->foreignId('approver_id')
    //         //     ->constrained('users')
    //         //     ->restrictOnDelete();
    //         $table->foreignId('approver_id')
    //             ->nullable()
    //             ->constrained('users')
    //             ->restrictOnDelete();

    //         $table->string('status');

    //         $table->string('signature')->nullable();

    //         $table->timestamp('approved_at')->nullable();

    //         $table->timestamps();
    //     });
    // }

    public function down(): void
    {
        Schema::dropIfExists('sppd_approval');
    }
};
