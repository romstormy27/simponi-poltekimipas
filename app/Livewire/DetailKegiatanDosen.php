<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory;

class DetailKegiatanDosen extends Component
{
    public Activity $kegiatan;
    
    // Variabel untuk modal pembatalan
    public $showModalBatal = false;
    public $alasan_pembatalan;

    public function mount($id)
    {
        $userId = auth()->id();

        // Perbaikan Bug 404: Cek akses untuk Ketua ATAU Anggota Tim
        $this->kegiatan = Activity::with(['members.user'])->where(function($query) use ($userId) {
            // Kondisi 1: Sebagai Pembuat/Ketua
            $query->where('user_id', $userId)
                  // Kondisi 2: Sebagai Anggota yang sudah menerima undangan
                  ->orWhereHas('members', function($subQuery) use ($userId) {
                      $subQuery->where('user_id', $userId)->where('status', 'accepted');
                  });
        })->findOrFail($id);
    }

    // Fungsi 1: Hapus Langsung (Hard Delete)
    public function hapusLangsung()
    {
        // Pastikan hanya bisa dihapus jika statusnya belum di-ACC Kaprodi
        if (in_array($this->kegiatan->status, ['draft', 'pending_kaprodi', 'perlu_revisi'])) {
            $this->kegiatan->delete();
            session()->flash('sukses', 'Kegiatan berhasil dihapus secara permanen.');
            return redirect()->route('pengajuan.riwayat');
        }
    }

    // Fungsi 2: Ajukan Pembatalan Resmi (Soft Cancel)
    public function ajukanPembatalan()
    {
        $this->validate([
            'alasan_pembatalan' => 'required|min:10'
        ]);

        // 1. Kita setel nilai kolomnya satu per satu secara langsung
        $this->kegiatan->status = 'pending_cancellation';
        $this->kegiatan->cancellation_reason = $this->alasan_pembatalan;
        
        // 2. Simpan secara paksa ke database (metode paling aman)
        $this->kegiatan->save();

        // 3. Catat ke dalam riwayat
        ActivityHistory::create([
            'activity_id' => $this->kegiatan->id,
            'status' => 'Pengajuan Pembatalan',
            'description' => 'Dosen mengajukan pembatalan dengan alasan: ' . $this->alasan_pembatalan
        ]);

        $this->showModalBatal = false;
        session()->flash('sukses', 'Pengajuan pembatalan telah dikirim ke Kaprodi.');
    }

    public function render()
    {
        $riwayat = ActivityHistory::where('activity_id', $this->kegiatan->id)->latest()->get();
        return view('livewire.detail-kegiatan-dosen', compact('riwayat'))->layout('layouts.app');
    }
}