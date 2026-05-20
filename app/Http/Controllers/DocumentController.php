<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\DocumentTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function generatePDF($activityId, $type, $action = 'preview')
    {
        // 1. Tarik Data Kegiatan beserta Dosen Ketua & Anggota yang sudah "ACC"
        $activity = Activity::with(['user', 'members' => function($query) {
            $query->where('role', 'anggota')
                ->wherein('status', ['accepted', 'pending']);
        }])->findOrFail($activityId);

        // Pastikan dokumen sudah diberi nomor oleh TU (Status Active)
        if (!$activity->document_number_task) {
            abort(403, 'Akses Ditolak: Dokumen belum diterbitkan nomornya oleh Tata Usaha.');
        }

        // 2. Tarik Template Aktif berdasarkan Jenis Surat
        $template = DocumentTemplate::where('type', $type)->where('is_active', true)->first();
        if (!$template) {
            abort(404, 'Template untuk surat ini belum disiapkan atau belum diaktifkan oleh Admin/TU.');
        }

        $content = $template->content;

        // 3. Kalkulasi Durasi Hari (Pembulatan Presisi)
        $start = Carbon::parse($activity->start_date)->startOfDay();
        $end = Carbon::parse($activity->end_date)->startOfDay();
        $durasi = (int) $start->diffInDays($end) + 1; // Memaksa format menjadi angka bulat (Integer)

        // 4. Susun Format Anggota (Mengubah array database menjadi List HTML)
        $daftarAnggota = '';
        if ($activity->members->count() > 0) {
            $daftarAnggota .= '<ol style="margin-top:0; padding-left: 20px;">';
            foreach ($activity->members as $member) {
                $daftarAnggota .= '<li>' . $member->user->name . '</li>';
            }
            $daftarAnggota .= '</ol>';
        } else {
            $daftarAnggota = '<span style="font-style: italic;">Tidak ada anggota (Kegiatan Mandiri)</span>';
        }

        // 5. CARI DATA KAPRODI SECARA DINAMIS BERDASARKAN PRODI DOSEN PENGUSUL
        $prodiDosen = $activity->user->program_studi;
        
        $kaprodi = \App\Models\User::role('Ketua Program Studi')
            ->where('program_studi', $prodiDosen)
            ->first();

        // Antisipasi jika data Kaprodi di prodi tersebut belum di-seed/dibuat
        $namaKaprodi = $kaprodi ? $kaprodi->name : '.......................................';
        $nipKaprodi = $kaprodi ? $kaprodi->username : '.......................................';
        $jabatanKaprodi = $prodiDosen ? 'Ketua Program Studi ' . $prodiDosen : 'Ketua Program Studi';

        // 5. Buat QR Code Verifikasi Digital (Disisipkan sebagai gambar Base64)
        $verifyUrl = route('dokumen.verifikasi', ['id' => $activity->id, 'type' => $type]);
        $qrCodeSvg = QrCode::format('svg')->size(100)->generate($verifyUrl);
        $qrImg = '<img src="data:image/svg+xml;base64,' . base64_encode($qrCodeSvg) . '" alt="QR Code Keamanan" />';

        // Tentukan Nomor Surat yang tepat
        $nomorSurat = $type == 'surat_tugas' ? $activity->document_number_task : $activity->document_number_permit;

        // 🟢 SUNTIKAN KODE LOGO INSTANSI
        $logoPath = public_path('logo-poltekimipas.png');
        $logoImg = '';
        if (file_exists($logoPath)) {
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $logoImg = 'data:image/png;base64,' . $logoBase64;
        }

        // 6. KAMUS TRANSLATOR (Menerjemahkan Placeholder menjadi Data Riil)
        $replacements = [
            '[LOGO_INSTANSI]' => $logoImg,
            '[NOMOR_SURAT]' => $nomorSurat,
            '[NOMOR_SURAT_TUGAS]' => $nomorSurat, // Mengakomodir template dari Anda
            '[JUDUL_KEGIATAN]' => $activity->title,
            '[JENIS_KEGIATAN]' => $activity->type == 'pengabdian' ? 'Pengabdian Kepada Masyarakat' : 'Penelitian',
            '[ALAMAT_KEGIATAN]' => $activity->location_or_target ?? '-',
            '[MITRA_SASARAN]' => $activity->partner ?? '-',
            '[TANGGAL_MULAI]' => $start->translatedFormat('d F Y'),
            '[TANGGAL_SELESAI]' => $end->translatedFormat('d F Y'),
            '[DURASI_KEGIATAN]' => $durasi . ' Hari',
            '[TARGET_LUARAN]' => $activity->target_output ?? '-',
            '[NAMA_DOSEN_KETUA]' => $activity->user->name,
            '[TANGGAL_SEKARANG]' => Carbon::now()->translatedFormat('d F Y'),
            '[TANGGAL_DISAHKAN]' => Carbon::now()->translatedFormat('d F Y'),
            '[TABEL_ANGGOTA]' => $daftarAnggota,
            '[NAMA_DOSEN_ANGGOTA]' => $daftarAnggota,
            '[QR_CODE]' => $qrImg,
            '[NAMA_KAPRODI]' => $namaKaprodi,
            '[NIP_KAPRODI]' => $nipKaprodi,
            '[JABATAN_KAPRODI]' => $jabatanKaprodi,
        ];

        // Ganti semua placeholder di dalam konten HTML template
        foreach ($replacements as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }

        // 7. Cetak menjadi PDF
        $pdf = Pdf::loadHTML($content);
        $pdf->setPaper('A4', 'portrait');

        $namaFile = strtoupper(str_replace('_', ' ', $type)) . '_' . Str::slug($activity->user->name) . '.pdf';

        if ($action == 'download') {
            return $pdf->download($namaFile); // Langsung unduh file
        }

        return $pdf->stream($namaFile); // Tampilkan pratinjau di browser
    }

    // Fungsi sederhana untuk halaman verifikasi QR Code
    public function verifikasi($id, $type)
    {
        $activity = Activity::findOrFail($id);
        return "Dokumen Sah. Diterbitkan oleh Tata Usaha Poltekimipas untuk Kegiatan: " . $activity->title;
    }
}