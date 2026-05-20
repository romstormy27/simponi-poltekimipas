<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 relative">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 dark:text-white">Pusat Manajemen Pengguna</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola data akun, penugasan hak akses, serta penyamaran sistem.</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button wire:click="bukaModalImport" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Impor CSV Massal
            </button>
            <button wire:click="tambahUser" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow transition">
                + Daftarkan User Baru
            </button>
        </div>
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
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Identitas Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kontak & Prodi</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hak Akses (Role)</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition {{ in_array($user->id, $selectedUsers) ? 'bg-blue-50/40 dark:bg-blue-900/10' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->id !== auth()->id())
                                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-xs font-medium">Anda</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">NIP: {{ $user->username ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            @if($user->program_studi)
                                <div class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $user->program_studi }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-100 dark:border-blue-900">
                                {{ $user->getRoleNames()->first() ?? 'Tidak ada Role' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                            @if($user->id !== auth()->id())
                                <button wire:click="masukSebagai({{ $user->id }})" class="px-2 py-0.5 bg-purple-50 text-purple-700 hover:bg-purple-100 dark:bg-purple-950/30 dark:text-purple-300 text-[11px] font-bold rounded border border-purple-200 dark:border-purple-900 transition">
                                    🔑 Masuk Sebagai
                                </button>
                            @endif
                            <button wire:click="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 font-bold text-xs transition">Edit</button>
                            <button wire:click="hapusUser({{ $user->id }})" wire:confirm="Hapus user ini secara permanen?" class="text-red-600 hover:text-red-900 font-bold text-xs transition">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($selectedUsers) > 0)
        <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-2xl px-4 animate-slide-up">
            <div class="bg-gray-900 text-white rounded-xl shadow-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border border-gray-800">
                <div class="text-sm font-bold text-blue-400">
                    💥 {{ count($selectedUsers) }} Pengguna Terpilih
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <select wire:model="bulkRoleTarget" class="bg-gray-800 text-white border-gray-700 rounded-md text-xs py-1.5 focus:ring-blue-500">
                        <option value="">-- Ubah Role Massal --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <button wire:click="bulkChangeRole" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-bold transition">
                        Terapkan
                    </button>

                    <div class="h-6 w-[1px] bg-gray-700 mx-1"></div>

                    <button wire:click="bulkDelete" wire:confirm="Yakin ingin menghapus semua user terpilih secara massal?" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-bold transition">
                        Hapus Massal
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $isEdit ? 'Ubah Data Pengguna' : 'Pendaftaran Akun Baru' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Alamat Email</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Username / NIP</label>
                    <input type="text" wire:model="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm" placeholder="Contoh: 199912272024041002">
                    @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Program Studi <span class="text-[10px] text-gray-400 normal-case">(Opsional)</span></label>
                    <select wire:model="program_studi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">-- Bukan Orang Prodi (Admin/TU) --</option>
                        <option value="Manajemen Teknologi Keimigrasian">Manajemen Teknologi Keimigrasian</option>
                        <option value="Hukum Keimigrasian">Hukum Keimigrasian</option>
                        <option value="Administrasi Keimigrasian">Administrasi Keimigrasian</option>
                    </select>
                    @error('program_studi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Hak Akses Mandat (Role)</label>
                    <select wire:model="roleSelected" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">-- Pilih Peran Jabatan --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roleSelected') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Kata Sandi {{ $isEdit ? '(Kosongkan jika tak diubah)' : '' }}</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white text-sm">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                <button wire:click="tutupModal" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-bold transition">Batal</button>
                <button wire:click="simpanUser" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-bold shadow transition">Eksekusi Data</button>
            </div>
        </div>
    </div>
    @endif

    @if($isOpenImport)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Impor Pengguna via File CSV</h3>
            <p class="text-xs text-gray-500 mb-4 leading-relaxed">Unggah berkas spreadsheet dengan ekstensi .csv. Sistem akan mendaftarkan baris data secara berurutan.</p>
            
            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg text-[11px] font-mono text-gray-600 dark:text-gray-400 mb-4 border border-gray-200 dark:border-gray-700">
                <p class="font-bold text-gray-800 dark:text-white mb-1">Format Kolom Wajib:</p>
                nama,username,email,role,password,program_studi<br>
                Budi Gunawan,1999122701,budi@poltekimipas.ac.id,Dosen Program Studi,password123,Hukum Keimigrasian<br>
                Siti Aminah,1995111502,siti@poltekimipas.ac.id,Ketua Program Studi,password456,Manajemen Teknologi Keimigrasian
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-500 uppercase">Pilih Berkas CSV</label>
                <input type="file" wire:model="csvFile" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('csvFile') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div wire:loading wire:target="csvFile" class="text-xs text-blue-600 mt-2 font-semibold animate-pulse">
                ⏳ Mengunggah dan memeriksa berkas...
            </div>

            <div class="flex justify-end space-x-2 mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                <button wire:click="tutupModalImport" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-bold transition">Batal</button>
                <button wire:click="importCsv" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-bold shadow transition" wire:loading.attr="disabled">
                    Mulai Ekstraksi Data
                </button>
            </div>
        </div>
    </div>
    @endif
</div>