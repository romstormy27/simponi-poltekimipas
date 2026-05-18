<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Simponi Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @role('Dosen Biasa')
                <div class="mb-4 text-gray-800 dark:text-gray-200">
                    <h3 class="text-lg font-bold">Ringkasan Kinerja Anda</h3>
                    <p class="text-sm">Pantau status kegiatan pengabdian dan penelitian Anda semester ini.</p>
                </div>
                
                <livewire:statistik-dosen />
            @endrole

            @unlessrole('Dosen Biasa')
                <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    Selamat datang di Panel Manajemen. Anda masuk sebagai: <strong>{{ auth()->user()->roles->pluck('name')->implode(', ') }}</strong>
                </div>
            @endunlessrole

        </div>
    </div>
</x-app-layout>
