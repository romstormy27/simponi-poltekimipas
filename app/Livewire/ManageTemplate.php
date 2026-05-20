<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DocumentTemplate;
use App\Models\AuditLog;

class ManageTemplate extends Component
{
    public $templateId, $name, $type = 'surat_tugas', $content, $is_active = false;
    public $isOpen = false;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:surat_izin,surat_tugas',
        'content' => 'required',
        'is_active' => 'required|boolean',
    ];

    // Otomatis ganti isi editor saat dropdown jenis surat diubah (hanya untuk pembuatan baru)
    public function updatedType($value)
    {
        if (!$this->isEdit) {
            $this->content = $this->getStarterTemplate($value);
        }
    }

    public function bukaModal() { $this->isOpen = true; }
    
    public function tutupModal() {
        $this->isOpen = false;
        $this->isEdit = false;
        $this->reset(['templateId', 'name', 'type', 'content', 'is_active']);
    }

    public function tambahTemplate() {
        $this->tutupModal();
        $this->type = 'surat_tugas';
        $this->content = $this->getStarterTemplate($this->type);
        $this->bukaModal();
    }

    public function simpanTemplate()
    {
        $this->validate();

        if ($this->is_active) {
            DocumentTemplate::where('type', $this->type)->update(['is_active' => false]);
        }

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'content' => $this->content,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            DocumentTemplate::find($this->templateId)->update($data);
            AuditLog::catat('Manajemen Template', "Memperbarui template dokumen: " . $this->name);
            session()->flash('sukses', 'Template dokumen berhasil diperbarui!');
        } else {
            DocumentTemplate::create($data);
            AuditLog::catat('Manajemen Template', "Membuat template dokumen baru: " . $this->name);
            session()->flash('sukses', 'Template dokumen baru berhasil disimpan!');
        }

        $this->tutupModal();
    }

    public function editTemplate($id)
    {
        $this->isEdit = true;
        $template = DocumentTemplate::findOrFail($id);
        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->type = $template->type;
        $this->content = $template->content;
        $this->is_active = $template->is_active;

        $this->bukaModal();
    }

    public function toggleStatus($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $statusBaru = !$template->is_active;

        if ($statusBaru) {
            DocumentTemplate::where('type', $template->type)->update(['is_active' => false]);
        }

        $template->update(['is_active' => $statusBaru]);
        AuditLog::catat('Manajemen Template', "Mengubah status aktifasi template: " . $template->name);
        session()->flash('sukses', 'Status aktifasi template berhasil diubah!');
    }

    public function hapusTemplate($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        AuditLog::catat('Manajemen Template', "Menghapus template dokumen: " . $template->name);
        $template->delete();
        session()->flash('sukses', 'Template berhasil dihapus secara permanen.');
    }

    // GENERATOR KODE HTML STANDAR KEMENKUMHAM / POLTEKIMIPAS
    private function getStarterTemplate($jenis)
    {
        // Komponen KOP SURAT (Sama untuk kedua jenis surat)
        $kopSurat = '
<table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 15px;">
    <tr>
        <td style="width: 15%; text-align: center; padding-bottom: 10px;">
            <img src="[LOGO_INSTANSI]" style="width: 80px;" alt="Logo">
        </td>
        <td style="width: 85%; text-align: center; line-height: 1.3; padding-bottom: 10px;">
            <span style="font-size: 14px;">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA</span><br>
            <span style="font-size: 12px;">BADAN PENGEMBANGAN SUMBER DAYA MANUSIA IMIGRASI DAN PEMASYARAKATAN</span><br>
            <strong style="font-size: 16px;">POLITEKNIK IMIGRASI DAN PEMASYARAKATAN</strong><br>
            <span style="font-size: 12px;">Jalan Satria - Sudirman, Tanah Tinggi, Kota Tangerang, Banten 15119</span><br>
            <span style="font-size: 12px;">Pos-el: <span style="color: blue; text-decoration: underline;">poltekimipas.office@gmail.com</span></span>
        </td>
    </tr>
</table>';

        // Komponen LAMPIRAN (Sama untuk kedua jenis surat)
        $lampiran = '
<div style="page-break-before: always; margin-top: 30px;">
    <p>Lampiran<br>NOMOR [NOMOR_SURAT]</p>
    <h4 style="text-align: center; margin-bottom: 20px;">DAFTAR NAMA DOSEN</h4>
    
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 15%; font-weight: bold; vertical-align: top;">KETUA</td>
            <td style="width: 5%; vertical-align: top;">:</td>
            <td style="width: 80%;">[NAMA_DOSEN_KETUA]</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top; padding-top: 10px;">ANGGOTA</td>
            <td style="vertical-align: top; padding-top: 10px;">:</td>
            <td style="padding-top: 10px;">
                [TABEL_ANGGOTA]
            </td>
        </tr>
    </table>
</div>';

        // ----------------------------------------------------
        // LOGIKA SURAT TUGAS
        // ----------------------------------------------------
        if ($jenis == 'surat_tugas') {
            return '<div style="font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; padding: 20px;">' . $kopSurat . '
<div style="text-align: center; margin-bottom: 20px;">
    <h3 style="margin: 0; text-decoration: underline;">SURAT TUGAS</h3>
    <p style="margin: 5px 0;">NOMOR [NOMOR_SURAT]</p>
</div>

<p>Bersamaan dengan surat ini, saya yang bertanda tangan di bawah ini:</p>

<table style="width: 100%; margin: 10px 0; margin-left: 20px;">
    <tr><td style="width: 150px;">Nama</td><td>: [NAMA_KAPRODI]</td></tr>
    <tr><td>NIP</td><td>: [NIP_KAPRODI]</td></tr>
    <tr><td>Jabatan</td><td>: [JABATAN_KAPRODI]</td></tr>
</table>

<p>Menugaskan kepada dosen (daftar nama terlampir)</p>
<p>Untuk:</p>

<ol style="margin-left: 20px; padding-left: 5px; list-style-type: decimal; text-align: justify;">
    <li style="margin-bottom: 8px;">Melaksanakan kegiatan [JENIS_KEGIATAN] dengan judul "[JUDUL_KEGIATAN]" bersama mitra [MITRA_SASARAN] di [ALAMAT_KEGIATAN];</li>
    <li style="margin-bottom: 8px;">Jangka waktu pelaksanaan tugas adalah dari tanggal [TANGGAL_MULAI] hingga [TANGGAL_SELESAI] ([DURASI_KEGIATAN]);</li>
    <li style="margin-bottom: 8px;">Setelah selesai melaksanakan tugas, yang bersangkutan wajib melaporkan hasil kegiatan kepada pimpinan beserta target luaran berupa [TARGET_LUARAN] yang telah ditentukan di awal kegiatan.</li>
</ol>

<p style="margin-top: 15px; text-align: justify;">Demikian surat tugas ini diberikan untuk dapat dilaksanakan dengan penuh tanggung jawab.</p>

<table style="width: 100%; margin-top: 40px;">
    <tr>
        <td style="width: 60%;">
            [QR_CODE]
        </td>
        <td style="width: 40%; text-align: left;">
            Tangerang, [TANGGAL_SEKARANG]<br>
            [JABATAN_KAPRODI],<br><br><br><br>
            <strong>[NAMA_KAPRODI]</strong><br>
            NIP [NIP_KAPRODI]
        </td>
    </tr>
</table>

<div style="margin-top: 30px;">
    <p style="margin: 0;">Tembusan:</p>
    <ol style="margin-top: 5px; padding-left: 20px;">
        <li>Direktur</li>
        <li>Wakil Direktur Bidang Akademik</li>
    </ol>
</div>' . $lampiran . '
</div>';
        }

        // ----------------------------------------------------
        // LOGIKA SURAT IZIN
        // ----------------------------------------------------
        return '<div style="font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; padding: 20px;">' . $kopSurat . '
<div style="text-align: center; margin-bottom: 20px;">
    <h3 style="margin: 0; text-decoration: underline;">SURAT IZIN</h3>
    <p style="margin: 5px 0;">NOMOR [NOMOR_SURAT]</p>
</div>

<p>Yang bertanda tangan di bawah ini:</p>

<table style="width: 100%; margin: 10px 0; margin-left: 20px;">
    <tr><td style="width: 150px;">Nama</td><td>: [NAMA_KAPRODI]</td></tr>
    <tr><td>NIP</td><td>: [NIP_KAPRODI]</td></tr>
    <tr><td>Jabatan</td><td>: [JABATAN_KAPRODI]</td></tr>
</table>

<p>Memberikan izin kepada dosen (daftar nama terlampir) untuk:</p>

<ol style="margin-left: 20px; padding-left: 5px; list-style-type: decimal; text-align: justify;">
    <li style="margin-bottom: 8px;">Melaksanakan kegiatan [JENIS_KEGIATAN] dengan judul "[JUDUL_KEGIATAN]" bersama mitra [MITRA_SASARAN] di [ALAMAT_KEGIATAN];</li>
    <li style="margin-bottom: 8px;">Jangka waktu pelaksanaan tugas adalah dari tanggal [TANGGAL_MULAI] hingga [TANGGAL_SELESAI] ([DURASI_KEGIATAN]);</li>
    <li style="margin-bottom: 8px;">Setelah selesai melaksanakan tugas, yang bersangkutan wajib melaporkan hasil kegiatan kepada pimpinan beserta target luaran berupa [TARGET_LUARAN] yang telah ditentukan di awal kegiatan.</li>
</ol>

<p style="margin-top: 15px; text-align: justify;">Demikian surat izin ini dibuat untuk digunakan sebagaimana mestinya.</p>

<table style="width: 100%; margin-top: 40px;">
    <tr>
        <td style="width: 60%;">
            [QR_CODE]
        </td>
        <td style="width: 40%; text-align: left;">
            Tangerang, [TANGGAL_SEKARANG]<br>
            [JABATAN_KAPRODI],<br><br><br><br>
            <strong>[NAMA_KAPRODI]</strong><br>
            NIP [NIP_KAPRODI]
        </td>
    </tr>
</table>

<div style="margin-top: 30px;">
    <p style="margin: 0;">Tembusan:</p>
    <ol style="margin-top: 5px; padding-left: 20px;">
        <li>Direktur</li>
        <li>Wakil Direktur Bidang Akademik</li>
    </ol>
</div>' . $lampiran . '
</div>';
    }

    public function render()
    {
        $templates = DocumentTemplate::latest()->get();
        return view('livewire.manage-template', compact('templates'))->layout('layouts.app');
    }
}