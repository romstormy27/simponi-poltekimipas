<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;

class AuditTrail extends Component
{
    use WithPagination; // Mengaktifkan fitur halaman (Next/Prev)

    public $search = '';
    public $filterAction = '';

    // Reset halaman jika sedang melakukan pencarian
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterAction() { $this->resetPage(); }

    public function render()
    {
        $logs = AuditLog::with('user')
            ->when($this->search, function($query) {
                $query->where('description', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function($q) {
                          $q->where('name', 'like', '%'.$this->search.'%');
                      });
            })
            ->when($this->filterAction, function($query) {
                $query->where('action', $this->filterAction);
            })
            ->latest()
            ->paginate(15); // Tampilkan 15 data per halaman

        return view('livewire.audit-trail', compact('logs'))->layout('layouts.app');
    }
}