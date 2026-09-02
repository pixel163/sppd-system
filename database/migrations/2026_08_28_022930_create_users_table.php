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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('nik')->unique();

            $table->foreignId('role_id')
                ->constrained('role')
                ->restrictOnDelete();

            $table->foreignId('department_id')
                ->constrained('department')
                ->restrictOnDelete();

            $table->foreignId('jabatan_id')
                ->constrained('jabatan')
                ->restrictOnDelete();

            $table->foreignId('golongan_id')
                ->constrained('golongan')
                ->restrictOnDelete();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
