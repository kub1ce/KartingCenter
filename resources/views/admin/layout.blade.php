<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Админ-панель</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-800 text-white flex flex-col flex-shrink-0">
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <span class="text-xl font-bold">Админ-панель</span>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.news.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.news.*') ? 'bg-gray-700' : '' }}">Новости</a>
                <a href="{{ route('admin.promotions.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.promotions.*') ? 'bg-gray-700' : '' }}">Акции</a>
                <a href="{{ route('admin.karts.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Карты</a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Бронирования</a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Пользователи</a>
            </nav>
            <div class="px-4 py-4 border-t border-gray-700">
                <a href="/" class="block px-4 py-2 rounded hover:bg-gray-700">← На сайт</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700">Выйти</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>