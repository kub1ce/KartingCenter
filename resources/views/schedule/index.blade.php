@php use Carbon\Carbon; @endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Расписание заездов</h1>

            <form method="GET" action="{{ route('schedule.index') }}" class="flex flex-wrap gap-4 mb-8">
                <div>
                    <label for="track_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Трасса
                    </label>
                    <select name="track_id" id="track_id"
                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        <option value="">Все трассы</option>
                        @foreach($tracks as $track)
                            <option value="{{ $track->id }}" {{ request(
                    'track_id') == $track->id ? 'selected' : '' }}>
                                {{ $track->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Дата
                    </label>
                    <input type="date"
                           name="date"
                           id="date"
                           value="{{ request('date') }}"
                           min="{{ today()->toDateString() }}"
                           max="{{ today()->addDays(14)->toDateString() }}"
                           class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                </div>

                <div class="flex items-end gap-2">
                    <x-primary-button>Применить</x-primary-button>

                    @if(request('track_id') || request('date'))
                        <a href="{{ route('schedule.index') }}">
                            <x-secondary-button>Сбросить</x-secondary-button>
                        </a>
                    @endif
                </div>
            </form>

            @if($slots->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Свободных слотов не найдено.</p>
            @else
                @foreach($slots as $date => $daySlots)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                            {{ Carbon::parse($date)->isoFormat('D MMMM, dddd') }}
                            @if(Carbon::parse($date)->isToday())
                                <span class="ml-2 text-xs font-medium text-green-600 dark:text-green-400">Сегодня</span>
                            @elseif(Carbon::parse($date)->isTomorrow())
                                <span class="ml-2 text-xs font-medium text-blue-600 dark:text-blue-400">Завтра</span>
                            @endif
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach($daySlots as $slot)
                                @php $isBusy = $slot->bookings->isNotEmpty(); @endphp

                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4
                                        {{ $isBusy ? 'opacity-60' : '' }}">

                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full mb-2
                                             {{ $isBusy
                                                ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                                : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ $isBusy ? 'Занято' : 'Свободно' }}
                                </span>

                                    <p class="text-base font-bold text-gray-900 dark:text-white">
                                        {{ Carbon::parse($slot->start_time)->format('H:i') }}
                                        –
                                        {{ Carbon::parse($slot->end_time)->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        {{ $slot->track->name }}
                                    </p>

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        от {{ number_format($slot->track->price_per_slot, 0, '.', ' ') }} ₽
                                    </p>

                                    @if(!$isBusy)
                                        @auth
                                            @can('is-client')
                                                <a href="{{ route('bookings.create', ['slot_id' => $slot->id]) }}">
                                                    <x-primary-button class="w-full justify-center text-xs">
                                                        Забронировать
                                                    </x-primary-button>
                                                </a>
                                            @endcan
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                                Войдите, чтобы забронировать
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
