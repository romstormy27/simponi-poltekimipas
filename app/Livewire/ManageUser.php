<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // <-- Wajib untuk fitur upload file
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class ManageUser extends Component
{
    use WithFileUploads;

    // Properti Form Single CRUD
    public $userId, $name, $email, $username, $program_studi, $password, $roleSelected;
    public $isOpen = false;
    public $isEdit = false;

    // Properti FITUR BULK ACTIONS (Baru)
    public $selectedUsers = []; // Menampung ID user yang dicentang
    public $selectAll = false;
    public $bulkRoleTarget = ''; // Target role untuk ubah massal

    // Properti FITUR IMPORT CSV (Baru)
    public $csvFile;
    public $isOpenImport = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'username' => 'required|string|max:255|unique:users,username,' . $this->userId, // 🟢
            'program_studi' => 'nullable|string|max:255', // 🟢
            'roleSelected' => 'required',
            'password' => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    // --- LOGIKA SINGLE CRUD (Lama) ---
    public function bukaModal() { $this->isOpen = true; }
    public function tutupModal() {
        $this->isOpen = false;
        $this->isEdit = false;
        $this->reset(['userId', 'name', 'email', 'username', 'program_studi', 'password', 'roleSelected']);
    }
    public function tambahUser() { $this->tutupModal(); $this->bukaModal(); }
    
    public function simpanUser()
    {
        $this->validate();
        $data = [
            'name' => $this->name, 
            'email' => $this->email,
            'username' => $this->username, // 🟢
            'program_studi' => $this->program_studi // 🟢
        ];
        
        if ($this->password) $data['password'] = bcrypt($this->password);

        if ($this->isEdit) {
            $user = User::find($this->userId);
            $user->update($data);
            $user->syncRoles([$this->roleSelected]);
            session()->flash('sukses', 'Data user berhasil diperbarui!');
        } else {
            $user = User::create($data);
            $user->assignRole($this->roleSelected);
            session()->flash('sukses', 'User baru berhasil didaftarkan!');
        }
        $this->tutupModal();
    }

    public function editUser($id)
    {
        $this->isEdit = true;
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username; // 🟢
        $this->program_studi = $user->program_studi; // 🟢
        $this->roleSelected = $user->getRoleNames()->first();
        $this->bukaModal();

        $targetUser = User::find($id);
        \App\Models\AuditLog::catat('Manajemen User', 'Superadmin melakukan edit user: ' . $targetUser->name);
    }

    public function hapusUser($id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus diri sendiri!');
            return;
        }
        User::find($id)->delete();
        \App\Models\AuditLog::catat('Manajemen User', 'Menghapus permanen pengguna dengan ID: ' . $id);
        session()->flash('sukses', 'User berhasil dihapus.');
    }

    public function masukSebagai($id)
    {
        if ($id === auth()->id()) return;
        session(['original_superadmin_id' => auth()->id()]);
        Auth::loginUsingId($id);

        $targetUser = User::find($id);
        \App\Models\AuditLog::catat('Sistem Keamanan', 'Superadmin melakukan Impersonate (Login Sebagai) user: ' . $targetUser->name);

        return redirect()->route('dashboard');
    }

    // ==========================================
    // EXTRA ACTION A: LOGIKA BULK ACTIONS (MASSAL)
    // ==========================================
    
    // Otomatis berjalan saat checkbox "Select All" di klik
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Centang semua user kecuali diri sendiri (Superadmin yang sedang login)
            $this->selectedUsers = User::where('id', '!=', auth()->id())
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    // Menjaga status selectAll jika user mencentang manual satu per satu hingga penuh
    public function updatedSelectedUsers()
    {
        $totalUserLain = User::where('id', '!=', auth()->id())->count();
        $this->selectAll = count($this->selectedUsers) === $totalUserLain;
    }

    public function bulkDelete()
    {
        if (empty($this->selectedUsers)) return;

        // Bersihkan ID jika tidak sengaja ada ID sendiri
        $idsToDelete = array_diff($this->selectedUsers, [auth()->id()]);

        User::whereIn('id', $idsToDelete)->delete();
        
        $totalDihapus = count($idsToDelete);
        $this->reset(['selectedUsers', 'selectAll']);
        session()->flash('sukses', "Berhasil menghapus {$totalDihapus} pengguna sekaligus!");

        \App\Models\AuditLog::catat('Manajemen User', "Menghapus massal {$totalDihapus} akun pengguna.");
    }

    public function bulkChangeRole()
    {
        if (empty($this->selectedUsers) || empty($this->bulkRoleTarget)) {
            session()->flash('error', 'Pilih target Peran (Role) terlebih dahulu!');
            return;
        }

        $users = User::whereIn('id', $this->selectedUsers)->get();
        foreach ($users as $user) {
            if ($user->id !== auth()->id()) {
                $user->syncRoles([$this->bulkRoleTarget]);
            }
        }

        $totalDiubah = $users->count();
        $this->reset(['selectedUsers', 'selectAll', 'bulkRoleTarget']);
        session()->flash('sukses', "Berhasil memperbarui peran {$totalDiubah} pengguna sekaligus!");

        \App\Models\AuditLog::catat('Manajemen User', "Mengubah peran secara massal untuk {$totalDiubah} pengguna menjadi {$this->bulkRoleTarget}.");
    }

    // ==========================================
    // EXTRA ACTION B: LOGIKA BULK IMPORT VIA CSV
    // ==========================================
    public function bukaModalImport() { $this->isOpenImport = true; }
    public function tutupModalImport() { $this->isOpenImport = false; $this->reset('csvFile'); }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048' // Batas 2MB
        ]);

        $path = $this->csvFile->getRealPath();
        $file = fopen($path, 'r');

        // Membaca baris pertama (Header: nama,email,role,password)
        $header = fgetcsv($file); 
        
        $suksesCount = 0;
        $skipCount = 0;

        // Looping membaca baris data ke bawah
        while (($row = fgetcsv($file)) !== false) {
            // Lewati jika kolom tidak lengkap (harus ada 4 kolom)
            if (count($row) < 4) {
                $skipCount++;
                continue;
            }

            $csv_nama = trim($row[0]);
            $csv_email = trim($row[1]);
            $csv_role = trim($row[2]);
            $csv_password = trim($row[3]);

            // VALIDASI INDUSTRI: Cek duplikasi email agar database tidak crash
            $exists = User::where('email', $csv_email)->exists();
            if ($exists || empty($csv_nama) || empty($csv_email)) {
                $skipCount++;
                continue; 
            }

            // Cek apakah Role yang ditulis di CSV valid terdaftar di sistem Spatie
            $roleExists = Role::where('name', $csv_role)->exists();
            if (!$roleExists) {
                $skipCount++;
                continue;
            }

            // Eksekusi Pembuatan User
            $newUser = User::create([
                'name' => $csv_nama,
                'email' => $csv_email,
                'password' => bcrypt($csv_password),
            ]);

            $newUser->assignRole($csv_role);
            $suksesCount++;
        }

        fclose($file);
        $this->tutupModalImport();

        session()->flash('sukses', "Proses Impor Selesai! {$suksesCount} User berhasil didaftarkan. ({$skipCount} Baris dilewati karena duplikasi/tidak valid).");

        \App\Models\AuditLog::catat('Manajemen User', "Mengimpor data CSV. Sukses: {$suksesCount} user, Dilewati: {$skipCount} baris.");
    }

    public function render()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();
        return view('livewire.manage-user', compact('users', 'roles'))->layout('layouts.app');
    }
}