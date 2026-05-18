<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <script>
            // Membaca preferensi tema sebelum halaman dirender sepenuhnya
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMPONI') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        @if(session()->has('original_superadmin_id'))
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white text-center py-2 px-4 text-sm font-bold shadow-lg flex justify-center items-center space-x-2 relative z-50 animate-bounce">
                <span>🛡️ Anda saat ini sedang dalam mode penyamaran sebagai: <strong class="underline">{{ auth()->user()->name }}</strong></span>
                <a href="{{ route('leave-impersonate') }}" class="ml-4 bg-white text-red-700 hover:bg-gray-100 px-3 py-1 rounded-full text-xs font-extrabold shadow-sm transition">
                    🚪 Akhiri Penyamaran &rarr;
                </a>
            </div>
        @endif
        
        <div class="flex h-screen bg-gray-100 dark:bg-gray-900 overflow-hidden">
            
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col overflow-hidden">
                
                <livewire:layout.navigation />

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow z-10">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>

        </div>
    </body>
</html>