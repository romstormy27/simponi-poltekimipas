<aside class="w-64 flex-shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:flex md:flex-col transition-all duration-300">
    
    <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 tracking-wider">
            SIMPONI
        </h1>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg group transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white font-bold' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('notifikasi.index') }}" class="flex items-center justify-between px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('notifikasi.index') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="font-medium">Notifikasi</span>
            </div>
            
            <livewire:sidebar-notif-badge />
        </a>

        @role('Dosen Program Studi')
        <a href="{{ route('pengajuan.buat') }}" class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('pengajuan.buat') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="font-medium">Ajukan Kegiatan</span>
        </a>

        <a href="{{ route('pengajuan.riwayat') }}" class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('pengajuan.riwayat') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
            <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span class="font-medium">List Kegiatan</span>
        </a>

        <a href="#" class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition">
            <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span class="font-medium">Logbook Harian</span>
        </a>
        @endrole

        @role('Ketua Program Studi')
        <a href="{{ route('approval.index') }}" class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('approval.index') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
            <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium">Antrean Approval</span>
        </a>
        @endrole

        @role('Kepala Sub Bagian TU')
        <a href="{{ route('tu.index') }}" class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('tu.index') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
            <svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="font-medium">Penomoran Surat</span>
        </a>
        @endrole

        @role('Super Admin')
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Konsol Admin</p>
            <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 text-purple-700 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-950/20 rounded-lg transition {{ request()->routeIs('admin.users') ? 'bg-purple-50 dark:bg-purple-950/30 font-bold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span class="font-medium">Manajemen User</span>
            </a>
            <a href="{{ route('admin.announcements') }}" class="flex items-center px-3 py-2.5 text-purple-700 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-950/20 rounded-lg transition {{ request()->routeIs('admin.announcements') ? 'bg-purple-50 dark:bg-purple-950/30 font-bold' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span class="font-medium">Papan Pengumuman</span>
            </a>
        </div>
        @endrole

    </nav>
</aside>