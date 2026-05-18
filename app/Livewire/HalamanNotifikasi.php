<?php

namespace App\Livewire;

use Livewire\Component;

class HalamanNotifikasi extends Component
{
    public function tandaiSudahBaca($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        $this->dispatch('notifDiperbarui'); // trigger badge di sidebar untuk berkurang
        return redirect($notification->data['link']); // langsung lempar ke halaman terkait
    }

    public function bersihkanSemua()
    {
        auth()->user()->notifications->markAsRead();
        $this->dispatch('notifDiperbarui');
    }

    public function render()
    {
        $allNotif = auth()->user()->notifications;
        return view('livewire.halaman-notifikasi', compact('allNotif'))->layout('layouts.app');
    }
}