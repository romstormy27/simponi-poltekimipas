<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory; // <--- Import tabel riwayat
use App\Notifications\SistemNotifikasi;

class ApprovalKaprodi extends Component
{
    public $showModalTolak = false;
    public $idKegiatanTolak;
    public $alasan_penolakan;

    // --- LOGIKA UNTUK PENGAJUAN BARU ---
    public function setujui($id)
    {
        $kegiatan = Activity::findOrFail($id);
        $kegiatan->update(['status' => 'pending_tu', 'rejection_note' => null]); 
        
        ActivityHistory::create(['activity_id' => $id, 'status' => 'Disetujui Kaprodi', 'description' => 'Pengajuan disetujui, diteruskan ke TU untuk penomoran surat.']);
        
        session()->flash('sukses', 'Kegiatan disetujui dan diteruskan ke TU.');
    }

    public function bukaModalTolak($id) { $this->idKegiatanTolak = $id; $this->alasan_penolakan = ''; $this->showModalTolak = true; }

    public function prosesTolak()
    {
        $this->validate(['alasan_penolakan' => 'required|min:10']);
        $kegiatan = Activity::findOrFail($this->idKegiatanTolak);
        $kegiatan->update(['status' => 'perlu_revisi', 'rejection_note' => $this->alasan_penolakan]);

        $kegiatan->user->notify(new SistemNotifikasi(
            'Pengajuan Perlu Revisi ⚠️',
            'Kaprodi mengembalikan pengajuan Anda: "' . $kegiatan->title . '" dengan catatan revisi.',
            route('pengajuan.riwayat')
        )); 
        
        ActivityHistory::create(['activity_id' => $kegiatan->id, 'status' => 'Dikembalikan (Revisi)', 'description' => 'Kaprodi meminta revisi: ' . $this->alasan_penolakan]);
        
        $this->showModalTolak = false; 
        session()->flash('error', 'Kegiatan dikembalikan ke dosen untuk direvisi.');
    }

    // --- LOGIKA UNTUK PERMINTAAN PEMBATALAN ---
    public function setujuiBatal($id)
    {
        $kegiatan = Activity::findOrFail($id);
        $kegiatan->update(['status' => 'cancelled']);

        $kegiatan->user->notify(new SistemNotifikasi(
            'Pembatalan Disetujui ✅',
            'Permohonan pembatalan kegiatan "' . $kegiatan->title . '" telah disetujui Kaprodi.',
            route('pengajuan.riwayat')
        )); 

        ActivityHistory::create(['activity_id' => $id, 'status' => 'Resmi Dibatalkan', 'description' => 'Kaprodi menyetujui permohonan pembatalan kegiatan.']);
        
        session()->flash('sukses', 'Permohonan pembatalan disetujui. Kegiatan resmi dihanguskan.');
    }

    public function tolakBatal($id)
    {
        $kegiatan = Activity::findOrFail($id);
        
        // Cek status sebelumnya: Jika sudah ada nomor surat kembali ke 'active', jika belum kembali ke 'pending_tu'
        $statusKembali = $kegiatan->document_number_task ? 'active' : 'pending_tu';
        
        $kegiatan->update([
            'status' => $statusKembali, 
            'cancellation_reason' => null // Hapus alasan batalnya
        ]); 

        ActivityHistory::create(['activity_id' => $id, 'status' => 'Pembatalan Ditolak', 'description' => 'Kaprodi menolak pembatalan. Dosen wajib melanjutkan kegiatan.']);
        
        session()->flash('error', 'Permohonan pembatalan ditolak. Kegiatan dikembalikan ke status berjalan.');
    }

    public function render()
    {
        // Ambil data antrean pengajuan baru DAN permohonan pembatalan
        $antrean = Activity::with('user')
            ->whereIn('status', ['pending_kaprodi', 'pending_cancellation'])
            ->latest()
            ->get();

        return view('livewire.approval-kaprodi', ['antrean' => $antrean]);
    }
}