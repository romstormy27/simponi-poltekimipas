<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;

class StatistikDosen extends Component
{
    public function render()
    {
        $userId = auth()->id();

        // Hitung statistik berdasarkan status
        $stats = [
            'total' => Activity::where('user_id', $userId)->count(),
            'pending' => Activity::where('user_id', $userId)->whereIn('status', ['draft', 'pending_kaprodi', 'pending_tu', 'pending_final_approval'])->count(),
            'aktif' => Activity::where('user_id', $userId)->where('status', 'active')->count(),
            'selesai' => Activity::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        return view('livewire.statistik-dosen', compact('stats'));
    }
}