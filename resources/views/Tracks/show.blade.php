@php use Carbon\Carbon; @endphp

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <nav class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                <a href="{{ route('tracks.index') }}" class="hover:underline">Трассы</a>
                <span class="mx-2">›</span>
                <span class="text-gray-900 dark:text-white">{{ $track->name }}</span>
            </nav>

            @php
                $difficulty = $track->difficulty->value ?? $track->difficulty;
                $diffLabel = match($difficulty) {
                    'Easy' => 'Легкая',
                    'Medium' => 'Средняя',
                    'Hard' => 'Сложная',
                    default => $difficulty,
                };
            @endphp

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $track->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $track->description }}</p>

                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li>Сложность: {{ $diffLabel }}</li>
                    <li>Длина: {{ number_format($track->length, 0, '.', ' ') }} м</li>
                    <li>Максимум участников: {{ $track->max_participants }}</li>
                    <li>Цена за слот: от {{ number_format($track->price_per_slot, 0, '.', ' ') }} ₽</li>
                </ul>
            </div>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Ближайшие слоты (14 дней)
            </h2>

            @if($slots->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Свободных слотов нет.</p>
            @else
                @foreach($slots as $date => $daySlots)
                    <div class="mb-6">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            {{ Carbon::parse($date)->isoFormat('D MMMM, dddd') }}
                            @if(Carbon::parse($date)->isToday())
                                <span class="ml-1 text-xs text-green-600 dark:text-green-400">Сегодня</span>
                            @endif
                        </h3>

                        <div class="flex flex-wrap gap-3">
                            @foreach($daySlots as $timeSlot)
                                @php $isBusy = $timeSlot->bookings->isNotEmpty(); @endphp

                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-3
                                        bg-white dark:bg-gray-800 {{ $isBusy ? 'opacity-50' : '' }}">

                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ Carbon::parse($timeSlot->start_time)->format('H:i') }}
                                        –
                                        {{ Carbon::parse($timeSlot->end_time)->format('H:i') }}
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        {{ $isBusy ? 'Занято' : 'Свободно' }}
                                    </p>

                                    @if(!$isBusy)
                                        @auth
                                            @can('is-client')
                                                <a href="{{ route('bookings.create', ['slot_id' => $timeSlot->id]) }}">
                                                    <x-primary-button class="text-xs">Забронировать</x-primary-button>
                                                </a>
                                            @endcan
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                Войдите
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</x-app-layout>
