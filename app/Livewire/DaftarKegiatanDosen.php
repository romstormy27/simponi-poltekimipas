<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityMember;
use App\Notifications\SistemNotifikasi;

class DaftarKegiatanDosen extends Component
{
    protected $listeners = ['kegiatanDisimpan' => '$refresh'];

    public function terimaUndangan($activityId)
    {
        ActivityMember::where('activity_id', $activityId)
            ->where('user_id', auth()->id())
            ->update(['status' => 'accepted']);

        $kegiatan = Activity::find($activityId);
        $ketua = $kegiatan->user; // Pembuat kegiatan
        
        $ketua->notify(new SistemNotifikasi(
            'Anggota Bergabung',
            auth()->user()->name . ' menerima undangan dan resmi bergabung di tim Anda.',
            route('pengajuan.detail', $activityId)
        ));
        
        session()->flash('sukses', 'Undangan tim berhasil diterima!');
    }

    public function tolakUndangan($activityId)
    {
        ActivityMember::where('activity_id', $activityId)
            ->where('user_id', auth()->id())
            ->update(['status' => 'rejected']);
        session()->flash('error', 'Undangan tim ditolak.');
    }

    public function render()
    {
        $userId = auth()->id();

        // Ambil kegiatan dimana user adalah pembuat (user_id di activities) 
        // ATAU ada di dalam tim (activity_members) tapi tidak berstatus rejected
        $kegiatan = Activity::with(['user', 'members' => function($q) use($userId) {
                $q->where('user_id', $userId);
            }])
            ->where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereHas('members', function($subQuery) use ($userId) {
                          $subQuery->where('user_id', $userId)->where('status', '!=', 'rejected');
                      });
            })
            ->latest()
            ->get();

        return view('livewire.daftar-kegiatan-dosen', compact('kegiatan'));
    }
}