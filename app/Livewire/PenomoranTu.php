<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory;
use App\Notifications\SistemNotifikasi;

class PenomoranTu extends Component
{
    // Array untuk menampung inputan nomor dari form berdasarkan ID kegiatan
    public $nomor_tugas = [];
    public $nomor_izin = [];

    public function simpanNomor($id)
    {
        // Validasi pastikan nomor diisi
        if(empty($this->nomor_tugas[$id]) || empty($this->nomor_izin[$id])) {
            session()->flash('error', 'Nomor Surat Tugas dan Surat Izin harus diisi!');
            return;
        }

        $kegiatan = Activity::findOrFail($id);
        
        // Update database dengan nomor surat dan ubah status ke 'active'
        $kegiatan->update([
            'document_number_task' => $this->nomor_tugas[$id],
            'document_number_permit' => $this->nomor_izin[$id],
            'status' => 'active'
        ]);

        ActivityHistory::create([
            'activity_id' => $kegiatan->id,
            'status' => 'Surat Resmi Terbit',
            'description' => "TU telah menerbitkan Surat Tugas ({$this->nomor_tugas[$id]}) dan Surat Izin ({$this->nomor_izin[$id]}). Kegiatan resmi berjalan."
        ]);

        $kegiatan->user->notify(new SistemNotifikasi(
            'Surat Tugas Terbit 🎉',
            'Tata Usaha telah menerbitkan nomor surat tugas untuk kegiatan Anda. Status sekarang Aktif!',
            route('pengajuan.detail', $id)
        )); 
        
        session()->flash('sukses', 'Nomor surat berhasil disimpan! Kegiatan sekarang berstatus Aktif.');

        \App\Models\AuditLog::catat('Administrasi TU', "Menerbitkan surat tugas nomor {$kegiatan->document_number_task} dan surat izin nomor {$kegiatan->document_number_permit} untuk kegiatan ID-{$kegiatan->id}");
    }

    public function render()
    {
        // Ambil data yang statusnya 'pending_tu' (sudah di-ACC Kaprodi)
        $antrean = Activity::with('user')->where('status', 'pending_tu')->latest()->get();

        return view('livewire.penomoran-tu', [
            'antrean' => $antrean
        ]);
    }
}