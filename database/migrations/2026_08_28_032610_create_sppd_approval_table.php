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
                ->constrained('users')
                ->restrictOnDelete();

            // $table->string('status');
            $table->string('status')->default('draft');

            $table->string('signature')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppd_approval');
    }
};
