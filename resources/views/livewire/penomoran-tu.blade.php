<div class="bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Antrean Penomoran Surat</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Berikan nomor surat tugas dan izin untuk kegiatan yang telah disetujui Kaprodi.</p>
    </div>

    @if (session()->has('sukses'))
        <div class="p-4 mx-6 mt-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">{{ session('sukses') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mx-6 mt-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto p-6">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dosen & Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Input Nomor Surat</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse ($antrean as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->user->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $item->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-2">
                                <label class="block text-xs text-gray-500 dark:text-gray-400">No. Surat Tugas</label>
                                <input type="text" wire:model="nomor_tugas.{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: ST-001/POLTEKIM/2026">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400">No. Surat Izin</label>
                                <input type="text" wire:model="nomor_izin.{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: SI-001/POLTEKIM/2026">
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center align-middle">
                            <button wire:click="simpanNomor({{ $item->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded shadow transition">
                                Terbitkan Surat
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-400">
                            Antrean kosong. Belum ada kegiatan yang menunggu penomoran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>