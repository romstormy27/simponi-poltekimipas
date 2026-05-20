<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    
    <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 dark:text-white flex items-center">
                📄 Pusat Template Dokumen Resmi
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola kerangka format Surat Tugas dan Surat Izin Kegiatan yang diterbitkan oleh sistem.</p>
        </div>
        <button wire:click="tambahTemplate" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow transition">
            + Buat Template Baru
        </button>
    </div>

    @if (session()->has('sukses'))
        <div class="p-4 bg-green-100 text-green-800 rounded-lg text-sm font-semibold shadow-sm">{{ session('sukses') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kerangka Template</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Surat</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Di Sistem</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terakhir Diubah</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($templates as $tpl)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $tpl->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2.5 py-0.5 rounded text-xs font-bold {{ $tpl->type == 'surat_tugas' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $tpl->type == 'surat_tugas' ? '💼 SURAT TUGAS' : '🛡️ SURAT IZIN' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleStatus({{ $tpl->id }})" class="px-3 py-1 rounded-full text-xs font-bold border transition
                                {{ $tpl->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-400 border-gray-300' }}
                            ">
                                {{ $tpl->is_active ? '🟢 Aktif Digunakan' : '⚪ Standby (Draf)' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-500">
                            {{ $tpl->updated_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            <button wire:click="editTemplate({{ $tpl->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs transition">Edit Format</button>
                            <button wire:click="hapusTemplate({{ $tpl->id }})" wire:confirm="Hapus template ini secara permanen?" class="text-red-600 hover:text-red-900 font-bold text-xs transition">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada template dokumen yang didaftarkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-6xl h-[90vh] flex flex-col border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">
                    {{ $isEdit ? 'Ubah Rencana Tata Letak Dokumen' : 'Perancangan Template Surat Instansi Baru' }}
                </h3>
                <button wire:click="tutupModal" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <div class="flex-1 flex overflow-hidden">
                
                <div class="w-2/3 p-6 space-y-4 overflow-y-auto border-r border-gray-100 dark:border-gray-700">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Klasifikasi Template</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-gray-900 dark:text-white" placeholder="Contoh: Format Surat Tugas Standard 2026">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori Surat Target</label>
                            <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-gray-900 dark:text-white">
                                <option value="surat_tugas">💼 Surat Tugas Kegiatan</option>
                                <option value="surat_izin">🛡️ Surat Izin Kegiatan</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col h-[52vh]">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Struktur Konten HTML Surat Resmi</label>
                        <textarea wire:model="content" class="w-full flex-1 rounded-md border-gray-300 font-mono text-xs p-4 bg-gray-900 text-emerald-400 shadow-inner focus:ring-2 focus:ring-indigo-500" style="resize: none;"></textarea>
                        @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Publikasi</label>
                        <select wire:model="is_active" class="block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-gray-900 dark:text-white">
                            <option value="0">⚪ Simpan sebagai draf (Standby)</option>
                            <option value="1">🟢 Aktifkan Sekarang (Mengganti template lama)</option>
                        </select>
                    </div>
                </div>

                <div class="w-1/3 bg-gray-50 dark:bg-gray-900/30 p-6 overflow-y-auto space-y-4">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                        <h4 class="text-xs font-extrabold text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">📋 Daftar Kode Kunci (Placeholder)</h4>
                        <p class="text-[11px] text-gray-400 mt-1 leading-normal">Gunakan kode di bawah ini ke dalam editor HTML kiri. Sistem otomatis menyalin data riil dosen saat diunduh.</p>
                    </div>

                    <div class="space-y-2 text-xs">
                        <p class="font-bold text-gray-700 dark:text-gray-300 text-[11px] border-l-2 border-gray-400 pl-1 uppercase">Nomor Administrasi TU</p>
                        <div class="bg-white dark:bg-gray-800 p-2 rounded border border-gray-200 dark:border-gray-700 font-mono text-[11px]">
                            <span class="text-indigo-600 dark:text-indigo-300 font-bold">[NOMOR_SURAT]</span><br>
                            <span class="text-gray-400 text-[10px]">Nomor resmi yang di-input oleh TU</span>
                        </div>

                        <p class="font-bold text-gray-700 dark:text-gray-300 text-[11px] border-l-2 border-gray-400 pl-1 uppercase mt-3">Rincian Kegiatan Dosen</p>
                        <div class="space-y-1.5 bg-white dark:bg-gray-800 p-2 rounded border border-gray-200 dark:border-gray-700 font-mono text-[11px]">
                            <div><strong class="text-indigo-600 dark:text-indigo-300">[JUDUL_KEGIATAN]</strong> <span class="text-gray-400 text-[10px] block">Judul riset / abdimas</span></div>
                            <div class="mt-1"><strong class="text-indigo-600 dark:text-indigo-300">[JENIS_KEGIATAN]</strong> <span class="text-gray-400 text-[10px] block">Penelitian / Pengabdian</span></div>
                            <div class="mt-1"><strong class="text-indigo-600 dark:text-indigo-300">[ALAMAT_KEGIATAN]</strong> <span class="text-gray-400 text-[10px] block">Lokasi penugasan</span></div>
                            <div class="mt-1"><strong class="text-indigo-600 dark:text-indigo-300">[MITRA_SASARAN]</strong> <span class="text-gray-400 text-[10px] block">Nama instansi mitra</span></div>
                            <div class="mt-1"><strong class="text-indigo-600 dark:text-indigo-300">[TARGET_LUARAN]</strong> <span class="text-gray-400 text-[10px] block">Target publikasi jurnal/haki</span></div>
                        </div>

                        <p class="font-bold text-gray-700 dark:text-gray-300 text-[11px] border-l-2 border-gray-400 pl-1 uppercase mt-3">Durasi Waktu</p>
                        <div class="space-y-1.5 bg-white dark:bg-gray-800 p-2 rounded border border-gray-200 dark:border-gray-700 font-mono text-[11px]">
                            <div><strong class="text-indigo-600 dark:text-indigo-300">[TANGGAL_MULAI]</strong></div>
                            <div><strong class="text-indigo-600 dark:text-indigo-300">[TANGGAL_SELESAI]</strong></div>
                            <div><strong class="text-indigo-600 dark:text-indigo-300">[DURASI_KEGIATAN]</strong> <span class="text-gray-400 text-[10px] block">Otomatis dihitung total hari</span></div>
                        </div>

                        <p class="font-bold text-gray-700 dark:text-gray-300 text-[11px] border-l-2 border-purple-500 pl-1 uppercase mt-3">Blok Komponen Kompleks</p>
                        <div class="space-y-1.5 bg-purple-50 dark:bg-purple-950/20 p-2 rounded border border-purple-200 dark:border-purple-900 font-mono text-[11px]">
                            <div><strong class="text-purple-700 dark:text-purple-400">[TABEL_ANGGOTA]</strong> <span class="text-gray-400 text-[10px] block">Injeksi tabel HTML daftar seluruh anggota yang sudah setuju</span></div>
                            <div class="mt-1"><strong class="text-purple-700 dark:text-purple-400">[QR_CODE]</strong> <span class="text-gray-400 text-[10px] block">Injeksi enkripsi QR Code tanda tangan digital pengaman sistem</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-2 bg-gray-50 dark:bg-gray-900/50">
                <button wire:click="tutupModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm font-bold transition">Batal</button>
                <button wire:click="simpanTemplate" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-bold shadow transition">Simpan Struktur Kerangka</button>
            </div>
        </div>
    </div>
    @endif
</div>