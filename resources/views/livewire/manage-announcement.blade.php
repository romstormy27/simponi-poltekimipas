<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative">
    
    <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 dark:text-white">Konsol Pengumuman Sistem</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Buat, siarkan, dan batasi jangkauan maklumat digital di dashboard pengguna.</p>
        </div>
        <button wire:click="bukaModal" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-bold shadow transition">
            + Buat Pengumuman Baru
        </button>
    </div>

    @if (session()->has('sukses'))
        <div class="p-4 bg-green-100 text-green-800 rounded-lg text-sm font-semibold shadow-sm">{{ session('sukses') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-red-100 text-red-800 rounded-lg text-sm font-semibold shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="w-12 px-6 py-3 text-left">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-200 focus:ring-opacity-50">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Judul & Pesan</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Tipe Alert</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Target Jangkauan</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($announcements as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition {{ in_array($item->id, $selectedAnnouncements) ? 'bg-purple-50/40 dark:bg-purple-900/10' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" wire:model.live="selectedAnnouncements" value="{{ $item->id }}" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-lg truncate">{{ $item->content }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider block text-center mx-auto min-w-[120px]
                                {{ $item->type == 'danger' ? 'bg-red-600 text-white' : '' }}
                                {{ $item->type == 'warning' ? 'bg-yellow-500 text-white' : '' }}
                                {{ $item->type == 'success' ? 'bg-emerald-600 text-white' : '' }}
                                {{ $item->type == 'info' ? 'bg-blue-600 text-white' : '' }}
                            ">
                                @if($item->type == 'info') INFORMASI UMUM @elseif($item->type == 'warning') PENTING @elseif($item->type == 'danger') SANGAT PENTING @elseif($item->type == 'success') INFORMASI LAINNYA @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-600 dark:text-gray-300">
                            {{ $item->target_role ?? '🌍 Semua Pengguna' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleStatus({{ $item->id }})" class="px-2.5 py-1 rounded-full text-xs font-bold border transition
                                {{ $item->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-300' }}
                            ">
                                {{ $item->is_active ? '🟢 Aktif Siar' : '⚪ Diarsipkan' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                            <button wire:click="editPengumuman({{ $item->id }})" class="text-blue-600 hover:text-blue-900 font-bold text-xs transition">Edit</button>
                            <button wire:click="hapusPengumuman({{ $item->id }})" wire:confirm="Hapus pengumuman ini?" class="text-red-600 hover:text-red-900 font-bold text-xs transition">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Papan pengumuman masih kosong.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($selectedAnnouncements) > 0)
        <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-xl px-4 animate-slide-up">
            <div class="bg-gray-900 text-white rounded-xl shadow-2xl p-4 flex items-center justify-between gap-4 border border-gray-800">
                <div class="text-sm font-bold text-purple-400">
                    📢 {{ count($selectedAnnouncements) }} Pengumuman Terpilih
                </div>
                
                <div class="flex items-center gap-2">
                    <select wire:model="bulkStatusTarget" class="bg-gray-800 text-white border-gray-700 rounded-md text-xs py-1.5 focus:ring-purple-500">
                        <option value="">-- Status Massal --</option>
                        <option value="1">🟢 Aktifkan Siar</option>
                        <option value="0">⚪ Arsipkan</option>
                    </select>
                    <button wire:click="bulkChangeStatus" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-xs font-bold transition">
                        Terapkan
                    </button>

                    <div class="h-6 w-[1px] bg-gray-700 mx-1"></div>

                    <button wire:click="bulkDelete" wire:confirm="Hakin ingin menghapus pengumuman terpilih secara massal?" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-bold transition">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $isEdit ? 'Ubah Materi Siaran' : 'Buat Maklumat Baru' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Judul Pengumuman</label>
                    <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 text-sm dark:bg-gray-900 dark:text-white" placeholder="Contoh: Pemeliharaan Server Sistem SIMPONI">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Isi Pesan Maklumat</label>
                    <textarea wire:model="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 text-sm dark:bg-gray-900 dark:text-white" placeholder="Ketikkan detail pengumuman resmi di sini..."></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Tingkat Urgensi</label>
                        <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 text-xs dark:bg-gray-900 dark:text-white">
                            <option value="info">🔵 INFORMASI UMUM</option>
                            <option value="warning">🟡 PENTING</option>
                            <option value="danger">🔴 SANGAT PENTING</option>
                            <option value="success">🟢 INFORMASI LAINNYA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Target Jangkauan</label>
                        <select wire:model="target_role" class="mt-1 block w-full rounded-md border-gray-300 text-xs dark:bg-gray-900 dark:text-white">
                            <option value="">🌍 Semua Pengguna</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Status Penyiaran</label>
                    <select wire:model="is_active" class="mt-1 block w-full rounded-md border-gray-300 text-xs dark:bg-gray-900 dark:text-white">
                        <option value="1">🟢 Langsung Siarkan Aktif</option>
                        <option value="0">⚪ Simpan Sebagai Draf</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                <button wire:click="tutupModal" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-md transition">Batal</button>
                <button wire:click="simpanPengumuman" class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-md shadow transition">Siarkan!</button>
            </div>
        </div>
    </div>
    @endif
</div>