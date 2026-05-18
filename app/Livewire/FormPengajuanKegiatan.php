<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\ActivityHistory;
use App\Models\ActivityMember; // <--- Import model baru
use App\Models\User; // <--- Import model User
use App\Notifications\SistemNotifikasi;

class FormPengajuanKegiatan extends Component
{
    // Variabel Form Lama
    // Pastikan baris ini persis seperti ini:
    public $title, $type, $target_output, $description, $location_or_target, $start_date, $end_date, $partner, $latitude, $longitude;
    
    // Variabel Form Baru (Untuk Pencarian Tim)
    public $keywordDosen = '';
    public $hasilPencarian = [];
    public $anggotaTerpilih = []; // Array untuk menampung dosen terpilih

    // Fungsi otomatis berjalan setiap ketik di kotak pencarian
    public function updatedKeywordDosen()
    {
        if (strlen($this->keywordDosen) > 1) {
            // Cari dosen selain dirinya sendiri dan yang belum dipilih
            $idTerpilih = array_column($this->anggotaTerpilih, 'id');
            $idTerpilih[] = auth()->id(); // Masukkan ID sendiri agar tidak muncul

            $this->hasilPencarian = User::role('Dosen Biasa')
                ->whereNotIn('id', $idTerpilih)
                ->where('name', 'like', '%' . $this->keywordDosen . '%')
                ->take(5)
                ->get();
        } else {
            $this->hasilPencarian = [];
        }
    }

    public function tambahAnggota($id, $nama)
    {
        $this->anggotaTerpilih[] = ['id' => $id, 'name' => $nama];
        $this->keywordDosen = ''; // Kosongkan pencarian
        $this->hasilPencarian = [];
    }

    public function hapusAnggota($index)
    {
        unset($this->anggotaTerpilih[$index]);
        $this->anggotaTerpilih = array_values($this->anggotaTerpilih); // Re-index array
    }

    public function simpanKegiatan()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:penelitian,pengabdian',
            'target_output' => 'required|string',
            'description' => 'required',
            'location_or_target' => 'required|string', // Alamat Lokasi
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'partner' => 'nullable|string|max:255', // Opsional
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $kegiatan = Activity::create([
            'user_id' => auth()->id(), 
            'title' => $this->title,
            'type' => $this->type,
            'target_output' => $this->target_output,
            'description' => $this->description,
            'location_or_target' => $this->location_or_target,
            'partner' => $this->partner,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => 'pending_kaprodi',
        ]);

        // ... (Logika simpan history dan anggota tim di bawahnya biarkan tetap sama) ...
        ActivityHistory::create([
            'activity_id' => $kegiatan->id,
            'status' => 'Pengajuan Baru',
            'description' => 'Dosen membuat pengajuan awal dan mengirimkannya ke Kaprodi.'
        ]);

        ActivityMember::create([
            'activity_id' => $kegiatan->id,
            'user_id' => auth()->id(),
            'role' => 'ketua',
            'status' => 'accepted'
        ]);

        foreach ($this->anggotaTerpilih as $anggota) {
            ActivityMember::create([
                'activity_id' => $kegiatan->id,
                'user_id' => $anggota['id'],
                'role' => 'anggota',
                'status' => 'pending'
            ]);
            
            $userAnggota = User::find($anggota['id']);
            $userAnggota->notify(new \App\Notifications\SistemNotifikasi(
                'Undangan Kolaborasi Tim',
                auth()->user()->name . ' mengundang Anda bergabung dalam kegiatan: "' . $this->title . '"',
                route('pengajuan.riwayat')
            ));
        }

        session()->flash('sukses', 'Pengajuan berhasil! Undangan telah dikirim ke anggota tim.');

        // Kirim Notifikasi ke Kaprodi
        $kaprodiUsers = \App\Models\User::role('Ketua Program Studi')->get();
        foreach ($kaprodiUsers as $kaprodi) {
            $kaprodi->notify(new \App\Notifications\SistemNotifikasi(
                'Pengajuan Kegiatan Baru 📝',
                auth()->user()->name . ' telah mengajukan kegiatan baru: "' . $this->title . '". Menunggu persetujuan Anda.',
                route('approval.index')
            ));
        }

        return redirect()->route('pengajuan.riwayat');
    }

    public function render() { return view('livewire.form-pengajuan-kegiatan'); }
}