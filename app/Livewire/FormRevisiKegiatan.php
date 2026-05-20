<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory;

class FormRevisiKegiatan extends Component
{
    public Activity $kegiatan;
    
    // Variabel form
    public $title, $type, $target_output, $description, $location_or_target, $start_date, $end_date;

    // Fungsi mount akan otomatis berjalan saat halaman dibuka untuk mengisi data lama
    public function mount($id)
    {
        $this->kegiatan = Activity::where('user_id', auth()->id())->findOrFail($id);
        
        $this->title = $this->kegiatan->title;
        $this->type = $this->kegiatan->type;
        $this->target_output = $this->kegiatan->target_output;
        $this->description = $this->kegiatan->description;
        $this->location_or_target = $this->kegiatan->location_or_target;
        $this->start_date = $this->kegiatan->start_date;
        $this->end_date = $this->kegiatan->end_date;
    }

    public function perbaruiKegiatan()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:penelitian,pengabdian',
            'target_output' => 'required|string',
            'description' => 'required',
            'location_or_target' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Simpan perubahan, ubah status, dan kosongkan catatan revisi
        $this->kegiatan->update([
            'title' => $this->title,
            'type' => $this->type,
            'target_output' => $this->target_output,
            'description' => $this->description,
            'location_or_target' => $this->location_or_target,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'pending_kaprodi', // Kembali ke meja Kaprodi
            'rejection_note' => null // Hapus catatan karena sudah direvisi
        ]);

        ActivityHistory::create([
            'activity_id' => $this->kegiatan->id,
            'status' => 'Revisi Dikirim',
            'description' => 'Dosen telah melakukan revisi dan mengajukan kembali ke Kaprodi.'
        ]);

        session()->flash('sukses', 'Revisi berhasil dikirim kembali ke Kaprodi.');

        // Kirim Notifikasi ke Kaprodi bahwa revisi sudah selesai
        $kaprodiUsers = \App\Models\User::role('Ketua Program Studi')
            ->where('program_studi', auth()->user()->program_studi)
            ->get();
            
        foreach ($kaprodiUsers as $kaprodi) {
            $kaprodi->notify(new \App\Notifications\SistemNotifikasi(
                'Revisi Dikembalikan 🔄',
                auth()->user()->name . ' telah mengirimkan kembali revisi dokumen untuk kegiatan: "' . $this->title . '".',
                route('approval.index')
            ));
        }

        return redirect()->route('pengajuan.riwayat'); // Lempar balik ke daftar
    }

    public function render()
    {
        return view('livewire.form-revisi-kegiatan')->layout('layouts.app');
    }
}