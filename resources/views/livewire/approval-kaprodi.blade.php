<div class="bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Antrean Persetujuan Kegiatan</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar kegiatan dosen yang menunggu persetujuan Anda.</p>
    </div>

    @if (session()->has('sukses'))
        <div class="p-4 mx-6 mt-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('sukses') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mx-6 mt-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto p-6">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Dosen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu Pelaksanaan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($antrean as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jenis: {{ ucfirst($item->type) }}</div>
                            
                            @if($item->status == 'pending_cancellation')
                                <div class="mt-2 p-3 bg-orange-50 dark:bg-orange-900/30 border-l-4 border-orange-500 rounded text-xs">
                                    <span class="font-bold text-orange-800 dark:text-orange-300">⚠️ Mengajukan Pembatalan:</span><br>
                                    <span class="text-orange-700 dark:text-orange-200 italic">"{{ $item->cancellation_reason }}"</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">s/d {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            
                            @if($item->status == 'pending_kaprodi')
                                <button wire:click="setujui({{ $item->id }})" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow transition mr-1">
                                    Setujui
                                </button>
                                <button wire:click="bukaModalTolak({{ $item->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow transition">
                                    Kembalikan
                                </button>
                                
                            @elseif($item->status == 'pending_cancellation')
                                <button wire:click="setujuiBatal({{ $item->id }})" wire:confirm="Yakin ingin membatalkan kegiatan ini secara resmi?" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow transition mr-1">
                                    Setujui Batal
                                </button>
                                <button wire:click="tolakBatal({{ $item->id }})" class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-bold rounded shadow transition">
                                    Tolak (Lanjut)
                                </button>
                            @endif
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p class="mt-2">Wah, antrean kosong. Belum ada pengajuan baru dari dosen.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($showModalTolak)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Alasan Pengembalian Dokumen</h3>
            
            <textarea wire:model="alasan_penolakan" rows="4" 
                      class="w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white mb-2" 
                      placeholder="Jelaskan bagian mana yang harus diperbaiki oleh dosen..."></textarea>
            @error('alasan_penolakan') <span class="text-red-500 text-xs block mb-4">{{ $message }}</span> @enderror

            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="$set('showModalTolak', false)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md text-sm font-semibold transition">Batal</button>
                <button wire:click="prosesTolak" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-semibold transition">Kirim Revisi</button>
            </div>
        </div>
    </div>
    @endif
</div>