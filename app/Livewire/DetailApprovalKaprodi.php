<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory;

class DetailApprovalKaprodi extends Component
{
    public Activity $kegiatan;
    public $showModalTolak = false;
    public $alasan_penolakan;

    public function mount($id)
    {
        // Memastikan dokumen yang ditinjau berada di bawah koridor wewenangnya
        $this->kegiatan = Activity::with(['user', 'members.user'])->whereIn('status', ['pending_kaprodi', 'pending_cancellation'])->findOrFail($id);

        // 🛡️ SISTEM KEAMANAN: Cek apakah Prodi dokumen SAMA dengan Prodi Kaprodi
        $prodiDokumen = $this->kegiatan->user->program_studi;
        $prodiKaprodi = auth()->user()->program_studi;

        if ($prodiDokumen !== $prodiKaprodi) {
            abort(403, "AKSES DITOLAK: Anda tidak berwenang meninjau dokumen dari Program Studi {$prodiDokumen}.");
        }
    }

    public function setujui()
    {
        $this->kegiatan->update(['status' => 'pending_tu', 'rejection_note' => null]);
        ActivityHistory::create(['activity_id' => $this->kegiatan->id, 'status' => 'Disetujui Kaprodi', 'description' => 'Pengajuan disetujui melalui peninjauan detail, diteruskan ke TU.']);
        
        session()->flash('sukses', 'Kegiatan berhasil disetujui.');

        // Kirim Notifikasi ke Tata Usaha (TU)
        $tuUsers = \App\Models\User::role('Kepala Sub Bagian TU')->get();
        foreach ($tuUsers as $tu) {
            $tu->notify(new \App\Notifications\SistemNotifikasi(
                'Penomoran Surat Menunggu 📇',
                'Kaprodi telah menyetujui kegiatan "' . $this->kegiatan->title . '". Mohon segera terbitkan Surat Tugas dan Izin.',
                route('tu.index')
            ));
        }

        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi menyetujui usulan kegiatan ID-{$this->kegiatan->id} berjudul: {$this->kegiatan->title}");

        return redirect()->route('approval.index');
    }

    public function prosesTolak()
    {
        $this->validate(['alasan_penolakan' => 'required|min:10']);
        $this->kegiatan->update(['status' => 'perlu_revisi', 'rejection_note' => $this->alasan_penolakan]);
        ActivityHistory::create(['activity_id' => $this->kegiatan->id, 'status' => 'Dikembalikan (Revisi)', 'description' => 'Kaprodi meminta revisi melalui peninjauan detail: ' . $this->alasan_penolakan]);

        $this->kegiatan->user->notify(new \App\Notifications\SistemNotifikasi(
            'Pengajuan Perlu Revisi ⚠️',
            'Kaprodi mengembalikan pengajuan Anda: "' . $this->kegiatan->title . '" dengan catatan revisi.',
            route('pengajuan.riwayat')
        ));
        
        session()->flash('error', 'Kegiatan dikembalikan ke dosen.');

        \App\Models\AuditLog::catat('Persetujuan Dokumen', "Kaprodi mengembalikan usulan kegiatan ID-{$this->kegiatan->id} dengan catatan revisi.");

        return redirect()->route('approval.index');
    }

    public function setujuiBatal()
    {
        $this->kegiatan->update(['status' => 'cancelled']);
        ActivityHistory::create(['activity_id' => $this->kegiatan->id, 'status' => 'Resmi Dibatalkan', 'description' => 'Kaprodi menyetujui pembatalan melalui peninjauan detail.']);
        
        session()->flash('sukses', 'Permohonan pembatalan disetujui.');
        return redirect()->route('approval.index');
    }

    public function tolakBatal()
    {
        $statusKembali = $this->kegiatan->document_number_task ? 'active' : 'pending_tu';
        $this->kegiatan->update(['status' => $statusKembali, 'cancellation_reason' => null]);
        ActivityHistory::create(['activity_id' => $this->kegiatan->id, 'status' => 'Pembatalan Ditolak', 'description' => 'Kaprodi menolak pembatalan melalui peninjauan detail.']);
        
        session()->flash('error', 'Permohonan pembatalan ditolak.');
        return redirect()->route('approval.index');
    }

    public function render()
    {
        $riwayat = ActivityHistory::where('activity_id', $this->kegiatan->id)->latest()->get();
        return view('livewire.detail-approval-kaprodi', compact('riwayat'))->layout('layouts.app');
    }
}