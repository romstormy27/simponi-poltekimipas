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
        $table->string('location_or_target'); 
        
        // --- TAMBAHKAN DUA BARIS BARU INI ---
        $table->date('start_date'); // Tanggal mulai kegiatan
        $table->date('end_date');   // Tanggal selesai kegiatan
        // ------------------------------------

        // catatan revisi dari kaprodi
        $table->text('rejection_note')->nullable(); // Menyimpan alasan dari Kaprodi
        
        $table->enum('status', [
            'draft',                  
            'pending_kaprodi',        
            'perlu_revisi',           // <-- STATUS BARU DITAMBAHKAN
            'pending_tu',             
            'active',                 
            'pending_final_approval', 
            'completed',
            'pending_cancellation', // <-- Status Baru: Menunggu ACC Batal dari Kaprodi
            'cancelled'             // <-- Status Baru: Resmi Dibatalkan               
        ])->default('draft');
        
        $table->string('document_number_task')->nullable();  
        $table->string('document_number_permit')->nullable(); 

        $table->text('cancellation_reason')->nullable();
        
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
