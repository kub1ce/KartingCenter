<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Control Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black font-sans antialiased text-gray-400">
    
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm md:hidden" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <aside x-bind:class="sidebarOpen ? 'translate-x-0' : ''" 
            class="fixed inset-y-0 left-0 z-30 w-64 bg-black/95 border-r border-white/10 flex flex-col flex-shrink-0 
            -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static">
            
            <div class="h-16 flex items-center justify-between border-b border-white/10 px-4">
                <span class="text-xl font-black tracking-widest text-red-500 uppercase" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);">
                    KART<span class="text-white">.ADMIN</span>
                </span>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                
                <p class="px-4 text-[10px] text-lime-400 font-bold uppercase tracking-widest mt-2 mb-3">Контент</p>
                
                <a href="{{ route('admin.news.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.news.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Новости
                </a>
                <a href="{{ route('admin.promotions.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.promotions.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Акции
                </a>
                <a href="{{ route('admin.tracks.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.tracks.*') ? 'bg-lime-500/10 text-lime-400 border-l-2 border-lime-500' : 'text-gray-500 hover:text-lime-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Трассы
                </a>
                
                @can('is-admin')
                <div class="border-t border-white/5 mt-8 pt-8"></div>
                <p class="px-4 text-[10px] text-red-500 font-bold uppercase tracking-widest mt-2 mb-3">Управление</p>
                
                <a href="{{ route('admin.karts.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.karts.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Карты
                </a>
                <a href="{{ route('admin.slots.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.slots.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Слоты
                </a>
                <a href="{{ route('admin.bookings.index') }}" @click="sidebarOpen = false"
                   class="block px-4 py-2.5 rounded text-xs font-bold uppercase tracking-widest transition 
                          {{ request()->routeIs('admin.bookings.*') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-gray-500 hover:text-red-400 hover:bg-white/5 border-l-2 border-transparent' }}">
                    Бронирования
                </a>
                <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false"
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

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="md:hidden flex items-center justify-between h-16 bg-black/80 border-b border-white/10 px-4 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-lime-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="text-xl font-black tracking-widest text-red-500 uppercase" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);">
                    KART<span class="text-white">.ADMIN</span>
                </span>
                <div class="w-6"></div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#050505] cyber-grid">
                <div class="container mx-auto px-4 md:px-6 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

    </div>
</body>
</html>