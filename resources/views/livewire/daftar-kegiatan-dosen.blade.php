<div class="mt-8 p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">

    @if (session()->has('sukses'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('sukses') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Riwayat Pengajuan Kegiatan</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul & Jenis</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($kegiatan as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($item->type) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">s/d {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status == 'pending_kaprodi')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Kaprodi</span>
                            @elseif($item->status == 'perlu_revisi')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">Perlu Revisi</span>
                            @elseif($item->status == 'pending_tu')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Penomoran Surat oleh TU</span>
                            @elseif($item->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Sedang Berjalan</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ str_replace('_', ' ', $item->status) }}</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @php
                                // Cek status keanggotaan user yang login saat ini di kegiatan tersebut
                                $memberInfo = $item->members->firstWhere('user_id', auth()->id());
                            @endphp

                            @if($memberInfo && $memberInfo->status == 'pending')
                                <div class="flex space-x-2">
                                    <button wire:click="terimaUndangan({{ $item->id }})" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded shadow text-xs font-bold transition">Ikut</button>
                                    <button wire:click="tolakUndangan({{ $item->id }})" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded shadow text-xs font-bold transition">Tolak</button>
                                </div>
                            @else
                                @if($item->status == 'perlu_revisi' && $item->user_id == auth()->id())
                                    <a href="{{ route('pengajuan.revisi', $item->id) }}" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded shadow text-xs font-bold transition">Revisi Sekarang</a>
                                @else
                                    <a href="{{ route('pengajuan.detail', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-bold transition">Lihat Detail &rarr;</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-400">
                            Belum ada kegiatan yang diajukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>