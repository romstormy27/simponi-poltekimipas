<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Announcement;
use Spatie\Permission\Models\Role;

class ManageAnnouncement extends Component
{
    public $announcementId, $title, $content, $type = 'info', $target_role = '', $is_active = true;
    public $isOpen = false;
    public $isEdit = false;

    // Properti Fitur Baru: BULK ACTIONS
    public $selectedAnnouncements = []; 
    public $selectAll = false;
    public $bulkStatusTarget = ''; // Target status massal (aktif/arsip)

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required',
        'type' => 'required|in:info,warning,danger,success',
        'target_role' => 'nullable',
        'is_active' => 'required|boolean',
    ];

    // --- LOGIKA SINGLE CRUD ---
    public function bukaModal() { $this->isOpen = true; }
    public function tutupModal() {
        $this->isOpen = false;
        $this->isEdit = false;
        $this->reset(['announcementId', 'title', 'content', 'type', 'target_role', 'is_active']);
    }

    public function simpanPengumuman()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'target_role' => $this->target_role ? $this->target_role : null,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            Announcement::find($this->announcementId)->update($data);
            session()->flash('sukses', 'Pengumuman berhasil diperbarui!');
        } else {
            Announcement::create($data);
            session()->flash('sukses', 'Pengumuman baru berhasil disiarkan!');
        }

        $this->tutupModal();
    }

    public function editPengumuman($id)
    {
        $this->isEdit = true;
        $item = Announcement::findOrFail($id);
        $this->announcementId = $item->id;
        $this->title = $item->title;
        $this->content = $item->content;
        $this->type = $item->type;
        $this->target_role = $item->target_role ?? '';
        $this->is_active = $item->is_active;

        $this->bukaModal();
    }

    public function toggleStatus($id)
    {
        $item = Announcement::find($id);
        $item->update(['is_active' => !$item->is_active]);
        session()->flash('sukses', 'Status aktifasi pengumuman berhasil diubah!');
    }

    public function hapusPengumuman($id)
    {
        Announcement::find($id)->delete();
        session()->flash('sukses', 'Pengumuman berhasil dihapus dari sistem.');
    }

    // ==========================================
    // LOGIKA BARU: BULK ACTIONS (TINDAKAN MASSAL)
    // ==========================================
    
    // Berjalan saat checkbox "Select All" di klik
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedAnnouncements = Announcement::pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedAnnouncements = [];
        }
    }

    // Sinkronisasi status selectAll jika dicentang satu per satu manual
    public function updatedSelectedAnnouncements()
    {
        $totalData = Announcement::count();
        $this->selectAll = count($this->selectedAnnouncements) === $totalData;
    }

    public function bulkDelete()
    {
        if (empty($this->selectedAnnouncements)) return;

        Announcement::whereIn('id', $this->selectedAnnouncements)->delete();
        
        $totalDihapus = count($this->selectedAnnouncements);
        $this->reset(['selectedAnnouncements', 'selectAll']);
        session()->flash('sukses', "Berhasil menghapus {$totalDihapus} pengumuman sekaligus!");
    }

    public function bulkChangeStatus()
    {
        if (empty($this->selectedAnnouncements) || $this->bulkStatusTarget === '') {
            session()->flash('error', 'Pilih tindakan status terlebih dahulu!');
            return;
        }

        Announcement::whereIn('id', $this->selectedAnnouncements)
            ->update(['is_active' => (boolean)$this->bulkStatusTarget]);

        $totalDiubah = count($this->selectedAnnouncements);
        $this->reset(['selectedAnnouncements', 'selectAll', 'bulkStatusTarget']);
        session()->flash('sukses', "Berhasil memperbarui status {$totalDiubah} pengumuman sekaligus!");
    }

    public function render()
    {
        $announcements = Announcement::latest()->get();
        $roles = Role::all();
        return view('livewire.manage-announcement', compact('announcements', 'roles'))->layout('layouts.app');
    }
}