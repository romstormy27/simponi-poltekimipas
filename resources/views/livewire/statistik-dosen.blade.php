<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Pengajuan</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $stats['total'] }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Menunggu Approval</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $stats['pending'] }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Sedang Berjalan</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $stats['aktif'] }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Selesai (Siap BKD)</h3>
        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $stats['selesai'] }}</p>
    </div>
</div>