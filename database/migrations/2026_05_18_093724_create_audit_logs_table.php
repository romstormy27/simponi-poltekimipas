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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Siapa pelakunya
            $table->string('action'); // Kategori aksi (Contoh: Manajemen User, Pengumuman, Sistem)
            $table->text('description'); // Detail spesifik
            $table->string('ip_address')->nullable(); // Alamat jaringan
            $table->string('user_agent')->nullable(); // Browser / Perangkat yang digunakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
