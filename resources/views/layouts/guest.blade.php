<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMPONI') }} - Login</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-hidden">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-900">
            
            <div class="w-full flex min-h-screen">
                
                <div class="hidden lg:flex lg:w-1/2 bg-blue-400 dark:bg-gray-950 items-center justify-center relative shadow-2xl z-10 overflow-hidden">
                    
                    <div class="absolute top-0 left-0 w-full h-full opacity-20 bg-[radial-gradient(circle_at_top_left,_var(--tw-gradient-stops))] from-blue-300 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 right-0 w-full h-full opacity-20 bg-[radial-gradient(circle_at_bottom_right,_var(--tw-gradient-stops))] from-blue-300 via-transparent to-transparent"></div>

                    <div class="relative z-20 px-12 text-center flex flex-col items-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo SIMPONI" class="w-40 h-40 mb-8 drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                        
                        <h1 class="text-5xl font-extrabold text-white tracking-widest mb-4 drop-shadow-lg">
                            SIMPONI
                        </h1>
                        
                        <div class="h-1 w-24 bg-blue-500 rounded-full mb-6"></div>
                        
                        <h2 class="text-xl font-bold text-blue-100 mb-2">
                            Sistem Informasi Manajemen
                        </h2>
                        <p class="text-md text-blue-200 font-medium leading-relaxed max-w-md">
                            Pengelolaan Performa Dosen Internal<br>
                            <span class="text-blue-400 font-bold mt-2 block">Politeknik Imigrasi dan Pemasyarakatan Indonesia</span>
                        </p>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6 sm:p-12 relative">
                    
                    <div class="w-full max-w-md">
                        <div class="lg:hidden text-center mb-8">
                            <img src="{{ asset('logo.png') }}" alt="Logo SIMPONI" class="w-24 h-24 mx-auto mb-4 drop-shadow-md">
                            <h2 class="text-3xl font-extrabold text-blue-800 dark:text-blue-400">SIMPONI</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Politeknik Imigrasi & Pemasyarakatan Indonesia</p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 px-8 py-10 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700">
                            
                            <div class="mb-8 text-center">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan masuk menggunakan akun Anda.</p>
                            </div>

                            {{ $slot }}
                            
                        </div>
                        
                        <div class="text-center mt-8 text-xs text-gray-400 dark:text-gray-500">
                            &copy; {{ date('Y') }} SIMPONI. Hak Cipta Dilindungi.<br>
                            Dikembangkan untuk keperluan internal akademik.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>