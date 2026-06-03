<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Control Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black font-sans antialiased text-gray-400">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-black/80 border-r border-white/10 flex flex-col flex-shrink-0">
            
            <div class="h-16 flex items-center justify-center border-b border-white/10 px-4">
                <span class="text-xl font-black tracking-widest text-red-500 uppercase" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);">
                    KART<span class="text-white">.ADMIN</span>
                </span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                
                <p class="px-4 text-[10px] text-lime-400 font-bold uppercase tracking-widest mt-2 mb-3">Контент</p>
                
                <a href="{{ route('admin.news.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.news.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Новости
                </a>
                <a href="{{ route('admin.promotions.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.promotions.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Акции
                </a>
                <a href="{{ route('admin.tracks.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.tracks.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Трассы
                </a>
                
                @can('is-admin')
                <div class="border-t border-white/5 mt-8 pt-8"></div>
                <p class="px-4 text-[10px] text-red-500 font-bold uppercase tracking-widest mt-2 mb-3">Управление</p>
                
                <a href="{{ route('admin.karts.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.karts.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Карты
                </a>
                <a href="{{ route('admin.slots.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.slots.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Слоты
                </a>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.bookings.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Бронирования
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.users.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Пользователи
                </a>
                @endcan

            </nav>

            <div class="px-4 py-4 border-t border-white/10 space-y-2">
                <a href="/" class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest text-gray-600 hover:text-white hover:bg-white/5 transition">
                    ← На сайт
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest text-red-500 hover:bg-red-500 hover:text-black transition">
                        Выйти из системы
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#050505] cyber-grid min-h-screen">
            <div class="container mx-auto px-6 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>