<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex flex-col min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-white/5 bg-black/60 backdrop-blur-md">
                <div class="h-px w-full bg-gradient-to-r from-transparent via-lime-500/50 to-transparent"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        
                        <div>
                            <h3 class="text-xl font-black text-white uppercase tracking-wider mb-4">
                                KART.<span class="text-lime-400">CENTER</span>
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Скорость, адреналин и неоновый свет. Твой картинг-центр нового поколения.
                            </p>
                        </div>

                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Контакты</h4>
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-center gap-3 text-gray-500 hover:text-lime-400 transition">
                                    <svg class="w-4 h-4 text-lime-500/70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                    +7 (343) 123-45-67
                                </li>
                                <li class="flex items-center gap-3 text-gray-500 hover:text-lime-400 transition">
                                    <svg class="w-4 h-4 text-lime-500/70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                    admin@karting.local
                                </li>
                                <li class="flex items-center gap-3 text-gray-500 hover:text-lime-400 transition">
                                    <svg class="w-4 h-4 text-lime-500/70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    г. Екатеринбург, ул. Мира, 32
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Режим работы</h4>
                            <ul class="space-y-3 text-sm text-gray-500">
                                <li class="flex justify-between"><span>Пн-Ср:</span> <span class="text-white font-bold">10:00 – 22:00</span></li>
                                <li class="flex justify-between"><span>Чт-Пт:</span> <span class="text-white font-bold">10:00 – 23:00</span></li>
                                <li class="flex justify-between"><span>Сб-Вс:</span> <span class="text-white font-bold">10:00 – 00:00</span></li>
                            </ul>
                        </div>

                    </div>

                    <div class="mt-10 pt-6 border-t border-white/5 flex flex-col md:flex-row justify-between items-center text-xs text-gray-600 uppercase tracking-widest font-bold">
                        <span>© {{ now()->year }} KART.CENTER. Все права защищены.</span>
                        <span class="mt-2 md:mt-0">Designed for speed</span>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>