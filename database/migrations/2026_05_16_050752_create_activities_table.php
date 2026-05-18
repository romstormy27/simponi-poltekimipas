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
    Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        
        $table->string('title'); 
        $table->enum('type', ['penelitian', 'pengabdian']); 
        $table->string('target_output'); 
        $table->text('description'); 
        
        // --- MODIFIKASI DAN TAMBAHKAN KOLOM BERIKUT ---
        $table->string('location_or_target'); // Sekarang murni untuk Alamat/Lokasi Kegiatan
        $table->string('partner')->nullable(); // Mitra Sasaran (Jika ada)
        $table->string('latitude')->nullable(); // Koordinat Latitude
        $table->string('longitude')->nullable(); // Koordinat Longitude
        // ----------------------------------------------
        
        $table->text('rejection_note')->nullable();
        $table->text('cancellation_reason')->nullable();
        
        $table->enum('status', [
            'draft', 'pending_kaprodi', 'perlu_revisi', 'pending_tu', 
            'active', 'pending_final_approval', 'completed', 'pending_cancellation', 'cancelled'
        ])->default('draft');
        
        $table->string('document_number_task')->nullable();  
        $table->string('document_number_permit')->nullable(); 
        
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
