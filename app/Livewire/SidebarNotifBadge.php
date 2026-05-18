<?php

namespace App\Livewire;

use Livewire\Component;

class SidebarNotifBadge extends Component
{
    // Menerima sinyal refresh jika ada interaksi
    protected $listeners = ['notifDiperbarui' => '$refresh'];

    public function render()
    {
        // Hitung jumlah notifikasi yang belum dibaca oleh user yang login
        $jumlahUnread = auth()->user()->unreadNotifications->count();

        return view('livewire.sidebar-notif-badge', compact('jumlahUnread'));
    }
}