<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Мои бронирования
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700 text-sm">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

            <div class="flex border-b border-gray-200 mb-6" x-data="{ tab: '{{ $tab }}' }">
                <a href="{{ route('bookings.index', ['tab' => 'upcoming']) }}"
                   class="px-5 py-3 text-sm font-medium border-b-2 transition
                          {{ $tab === 'upcoming' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Предстоящие
                    @if ($upcoming->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold
                                     {{ $tab === 'upcoming' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $upcoming->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('bookings.index', ['tab' => 'history']) }}"
                   class="px-5 py-3 text-sm font-medium border-b-2 transition
                          {{ $tab === 'history' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    История
                    @if ($history->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold
                                     {{ $tab === 'history' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $history->count() }}
                        </span>
                    @endif
                </a>
            </div>

            @if ($tab === 'upcoming')
                @if ($upcoming->isEmpty())
                    <div class="text-center py-16">
                        <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">У вас нет предстоящих бронирований</p>
                        <a href="{{ route('schedule.index') }}"
                           class="mt-4 inline-block px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            Посмотреть расписание
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($upcoming as $booking)
                            @include('bookings._card', ['booking' => $booking])
                        @endforeach
                    </div>
                @endif
            @endif

            @if ($tab === 'history')
                @if ($history->isEmpty())
                    <div class="text-center py-16">
                        <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">История бронирований пуста</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($history as $booking)
                            @include('bookings._card', ['booking' => $booking])
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
