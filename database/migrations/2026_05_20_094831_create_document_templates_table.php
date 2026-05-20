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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Template Surat Tugas Penelitian 2026"
            $table->enum('type', ['surat_izin', 'surat_tugas']);
            $table->longText('content'); // Berisi kode HTML/Rich text dari surat
            $table->boolean('is_active')->default(false); // Hanya 1 template aktif per tipe surat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
