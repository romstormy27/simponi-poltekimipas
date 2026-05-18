<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Audit Trail (Log Aktivitas Global)
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pantau seluruh aktivitas krusial pengguna di dalam sistem demi keamanan dan kepatuhan.</p>
        </div>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <select wire:model.live="filterAction" class="rounded-lg border-gray-300 text-sm dark:bg-gray-900 dark:text-white">
                <option value="">Semua Kategori</option>
                <option value="Manajemen User">Manajemen User</option>
                <option value="Pengumuman">Pengumuman</option>
                <option value="Sistem Keamanan">Sistem Keamanan</option>
                <option value="Persetujuan Dokumen">Persetujuan Dokumen</option>
                <option value="Administrasi TU">Administrasi TU</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau aktivitas..." class="rounded-lg border-gray-300 text-sm dark:bg-gray-900 dark:text-white w-full sm:w-64">
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase w-48">Waktu (Timestamp)</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Pelaku / Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Deskripsi Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                {{ $log->created_at->format('d M Y - H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->user ? $log->user->name : 'Sistem Otomatis' }}</div>
                                <div class="text-[10px] text-gray-500">{{ $log->user ? $log->user->email : 'System' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-700 dark:text-gray-300">{{ $log->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada catatan aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $logs->links() }}
        </div>
    </div>
</div>