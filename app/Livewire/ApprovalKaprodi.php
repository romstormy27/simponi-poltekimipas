<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory; 
use App\Notifications\SistemNotifikasi;

class ApprovalKaprodi extends Component
{
    public $showModalTolak = false;
    public $idKegiatanTolak; // Variabel penampung utama yang konsisten
    public $alasan_penolakan;

    // --- LOGIKA UNTUK PENGAJUAN BARU ---
    public function setujui($id)
    {
        $kegiatan = Activity::findOrFail($id);
        $kegiatan->update(['status' => 'pending_tu', 'rejection_note' => null]); 
        
        ActivityHistory::create([
            'activity_id' => $id, 
            'status' => 'Disetujui Kaprodi', 
            'description' => 'Pengajuan disetujui, diteruskan ke TU untuk penomoran surat.'
        ]);
        
        // Catat Audit Log Global
        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi menyetujui usulan kegiatan ID-{$kegiatan->id} berjudul: \"{$kegiatan->title}\" langsung dari tabel antrean.");

        // Kirim Notifikasi ke Tata Usaha (TU)
        $tuUsers = \App\Models\User::role('Kepala Sub Bagian TU')->get();
        foreach ($tuUsers as $tu) {
            $tu->notify(new \App\Notifications\SistemNotifikasi(
                'Penomoran Surat Menunggu 📇',
                'Kaprodi telah menyetujui kegiatan "' . $kegiatan->title . '". Mohon segera terbitkan Surat Tugas dan Izin.',
                route('tu.index')
            ));
        }

        session()->flash('sukses', 'Kegiatan disetujui dan diteruskan ke TU.');
    }

    public function bukaModalTolak($id) 
    { 
        $this->idKegiatanTolak = $id;
        $this->alasan_penolakan = '';
        $this->showModalTolak = true; 
    }

    public function prosesTolak()
    {
        $this->validate([
            'alasan_penolakan' => 'required|min:10'
        ], [
            'alasan_penolakan.required' => 'Alasan perbaikan wajib diisi.',
            'alasan_penolakan.min' => 'Catatan revisi terlalu pendek, minimal 10 karakter.'
        ]);

        $kegiatan = Activity::findOrFail($this->idKegiatanTolak);
        $kegiatan->update([
            'status' => 'perlu_revisi', 
            'rejection_note' => $this->alasan_penolakan
        ]);

        // Kirim Notifikasi ke Dosen Pengusul
        $kegiatan->user->notify(new SistemNotifikasi(
            'Pengajuan Perlu Revisi ⚠️',
            'Kaprodi mengembalikan pengajuan Anda: "' . $kegiatan->title . '" dengan catatan revisi.',
            route('pengajuan.riwayat')
        )); 
        
        ActivityHistory::create([
            'activity_id' => $kegiatan->id, 
            'status' => 'Dikembalikan (Revisi)', 
            'description' => 'Kaprodi meminta revisi dari tabel antrean: ' . $this->alasan_penolakan
        ]);

        // ✅ PERBAIKAN: Menggunakan variabel lokal $kegiatan yang valid
        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi mengembalikan usulan kegiatan ID-{$kegiatan->id} berjudul: \"{$kegiatan->title}\" untuk direvisi dosen.");
        
        $this->showModalTolak = false; 
        
        // ✅ PERBAIKAN: Mengosongkan properti yang benar
        $this->reset(['alasan_penolakan', 'idKegiatanTolak']);
        
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

        ActivityHistory::create([
            'activity_id' => $id, 
            'status' => 'Resmi Dibatalkan', 
            'description' => 'Kaprodi menyetujui permohonan pembatalan kegiatan dari tabel antrean.'
        ]);
        
        // Tambahan Audit Log Pembatalan
        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi menyetujui pembatalan resmi kegiatan ID-{$kegiatan->id} berjudul: \"{$kegiatan->title}\".");
        
        session()->flash('sukses', 'Permohonan pembatalan disetujui. Kegiatan resmi dihanguskan.');
    }

    public function tolakBatal($id)
    {
        $kegiatan = Activity::findOrFail($id);
        
        $statusKembali = $kegiatan->document_number_task ? 'active' : 'pending_tu';
        
        $kegiatan->update([
            'status' => $statusKembali, 
            'cancellation_reason' => null 
        ]); 

        ActivityHistory::create([
            'activity_id' => $id, 
            'status' => 'Pembatalan Ditolak', 
            'description' => 'Kaprodi menolak pembatalan dari tabel antrean. Dosen wajib melanjutkan kegiatan.'
        ]);

        // Tambahan Audit Log Penolakan Batal
        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi menolak permohonan pembatalan kegiatan ID-{$kegiatan->id} berjudul: \"{$kegiatan->title}\".");
        
        session()->flash('error', 'Permohonan pembatalan ditolak. Kegiatan dikembalikan ke status berjalan.');
    }

    public function render()
    {
        $antrean = Activity::with('user')
            ->whereIn('status', ['pending_kaprodi', 'pending_cancellation'])
            ->latest()
            ->get();

        return view('livewire.approval-kaprodi', ['antrean' => $antrean]);
    }
}