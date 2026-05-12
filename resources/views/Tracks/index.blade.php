<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Трассы</h1>

            @if($tracks->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Трассы пока не добавлены.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($tracks as $track)
                        @php
                            $difficulty = $track->difficulty->value ?? $track->difficulty;
                            $diffLabel = match($difficulty) {
                                'Easy' => 'Легкая',
                                'Medium' => 'Средняя',
                                'Hard' => 'Сложная',
                                default => $difficulty,
                            };
                        @endphp

                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5">

                            <div class="flex items-start justify-between mb-3">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $track->name }}</h2>
                                <span
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $diffLabel }}</span>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $track->description }}
                            </p>

                            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                                <li>Длина: {{ number_format($track->length, 0, '.', ' ') }} м</li>
                                <li>Максимум участников: {{ $track->max_participants }}</li>
                                <li>Цена за слот: от {{ number_format($track->price_per_slot, 0, '.', ' ') }} ₽</li>
                            </ul>

                            <div class="flex gap-2">
                                <a href="{{ route('tracks.show', $track) }}">
                                    <x-primary-button>Расписание трассы</x-primary-button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
