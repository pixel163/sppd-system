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
        Schema::create('ilpd_approval', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ilpd_id')
                ->nullable()
                ->constrained('ilpd')
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
        Schema::dropIfExists('ilpd_approval');
    }
};
