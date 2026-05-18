<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Simponi Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-blue-500 overflow-hidden">
                <div class="p-6 sm:p-8 flex items-center bg-gradient-to-r from-blue-50/50 to-transparent dark:from-blue-900/10">
                    <div class="hidden sm:flex p-4 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 mr-6 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Selamat Datang, {{ auth()->user()->name }}! 👋
                        </h2>
                        <p class="text-md text-gray-600 dark:text-gray-400 mt-2">
                            Saat ini Anda login sebagai 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ auth()->user()->getRoleNames()->first() ?? 'Pengguna Sistem' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            @role('Dosen Program Studi')
                <div class="mb-4 text-gray-800 dark:text-gray-200">
                    <h3 class="text-lg font-bold">Ringkasan Kinerja Anda</h3>
                    <p class="text-sm">Pantau status kegiatan pengabdian dan penelitian Anda semester ini.</p>
                </div>
                
                <livewire:statistik-dosen />
            @endrole

            @role('Ketua Program Studi')
            @php
                // Logika Pengambilan Data Kaprodi
                $kaprodiPending = \App\Models\Activity::where('status', 'pending_kaprodi')->count();
                $kaprodiBatal = \App\Models\Activity::where('status', 'pending_cancellation')->count();
                $kaprodiAktif = \App\Models\Activity::where('status', 'active')->count();
                $kaprodiTotalAcc = \App\Models\Activity::whereIn('status', ['pending_tu', 'active', 'pending_final_approval', 'completed'])->count();
            @endphp

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Ringkasan Kinerja Program Studi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center transition hover:shadow-md">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Persetujuan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kaprodiPending }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center transition hover:shadow-md">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mr-4 relative">
                            @if($kaprodiBatal > 0)
                                <span class="absolute top-0 right-0 h-3 w-3 rounded-full bg-red-500 animate-ping"></span>
                                <span class="absolute top-0 right-0 h-3 w-3 rounded-full bg-red-600 border-2 border-white dark:border-gray-800"></span>
                            @endif
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Permohonan Batal</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kaprodiBatal }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center transition hover:shadow-md">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kegiatan Berjalan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kaprodiAktif }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center transition hover:shadow-md">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Disetujui</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kaprodiTotalAcc }}</p>
                        </div>
                    </div>

                </div>
            </div>
            @endrole

            @role('Kepala Sub Bagian TU')
            @php
                // Logika Pengambilan Data TU
                $tuPending = \App\Models\Activity::where('status', 'pending_tu')->count();
                $tuTerbit = \App\Models\Activity::whereNotNull('document_number_task')->count();
                $tuSelesai = \App\Models\Activity::where('status', 'completed')->count();
            @endphp

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Status Administrasi & Penomoran</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-md p-6 text-white relative overflow-hidden transition hover:shadow-lg">
                        <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                        <div class="relative z-10">
                            <p class="text-orange-100 font-medium text-sm">Antrean Penomoran Surat</p>
                            <p class="text-4xl font-extrabold mt-1">{{ $tuPending }}</p>
                            <p class="text-xs mt-2 text-orange-200">Dokumen menunggu aksi Anda</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden transition hover:shadow-lg">
                        <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        <div class="relative z-10">
                            <p class="text-blue-100 font-medium text-sm">Surat Tugas/Izin Diterbitkan</p>
                            <p class="text-4xl font-extrabold mt-1">{{ $tuTerbit }}</p>
                            <p class="text-xs mt-2 text-blue-200">Total dokumen yang telah rilis</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden transition hover:shadow-lg">
                        <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                        <div class="relative z-10">
                            <p class="text-teal-100 font-medium text-sm">Arsip Kegiatan Selesai</p>
                            <p class="text-4xl font-extrabold mt-1">{{ $tuSelesai }}</p>
                            <p class="text-xs mt-2 text-teal-200">Dokumen rampung (BKD)</p>
                        </div>
                    </div>

                </div>
            </div>
            @endrole

            <!-- @unlessrole('Dosen Program Studi')
                <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    Selamat datang di Panel Manajemen. Anda masuk sebagai: <strong>{{ auth()->user()->roles->pluck('name')->implode(', ') }}</strong>
                </div>
            @endunlessrole -->

        </div>
    </div>
</x-app-layout>
