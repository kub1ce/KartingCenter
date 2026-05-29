<x-app-layout>
    <div class="bg-indigo-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl">
                Картинг-Центр
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-indigo-200">
                Ощути драйв скорости на лучших трассах города. Бронируйте заезды онлайн в пару кликов!
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="{{ route('schedule.index') }}" class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10 transition">
                    Полное расписание
                </a>
                <a href="{{ route('schedule.index', ['date' => now()->format('Y-m-d')]) }}" class="px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-400 md:py-4 md:text-lg md:px-10 transition border-indigo-400">
                    Забронировать сегодня
                </a>
            </div>
        </div>
    </div>

    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Почему выбирают нас?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">1.</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Профессиональные карты</h3>
                    <p class="text-gray-600 text-sm">Детские, одноместные и двухместные карты. Регулярное ТО и проверка безопасности перед каждым заездом.</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">2.</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Трассы любой сложности</h3>
                    <p class="text-gray-600 text-sm">От новичков до профи. Выбирайте трассу под свой уровень подготовки.</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="text-4xl mb-4">3.</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Удобное бронирование</h3>
                    <p class="text-gray-600 text-sm">Бронируйте заезды онлайн без звонков. Выбирайте удобное время и тип карта.</p>
                </div>
            </div>
        </div>
    </div>

    @if($promotions->isNotEmpty())
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Горячие акции</h2>
                <a href="{{ route('public.promotions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Все акции &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($promotions as $promo)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-red-600 text-white text-center py-2 font-bold text-lg">
                            -{{ $promo->discount_percent }}%
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $promo->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($promo->description, 100) }}</p>
                            <p class="text-xs text-gray-400">Действует до {{ \Carbon\Carbon::parse($promo->end_date)->format('d.m.Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="py-16 bg-white text-center">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Готовы к заезду?</h2>
        <a href="{{ route('schedule.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
            Перейти к расписанию
            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
        </a>
    </div>
</x-app-layout>