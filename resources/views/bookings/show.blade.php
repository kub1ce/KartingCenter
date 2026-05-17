@php use Carbon\Carbon; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Бронирование #{{ $booking->id }}
            </h2>
            <a href="{{ route('bookings.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Все бронирования
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

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

            @php
                $statusConfig = match($booking->status) {
                    'Pending'   => ['label' => 'Ожидает подтверждения', 'class' => 'bg-yellow-100 text-yellow-700', 'icon' => '⏳'],
                    'Confirmed' => ['label' => 'Подтверждено', 'class' => 'bg-green-100 text-green-700', 'icon' => '✅'],
                    'Cancelled' => ['label' => 'Отменено', 'class' => 'bg-red-100 text-red-700', 'icon' => '❌'],
                    'Completed' => ['label' => 'Завершено', 'class' => 'bg-gray-100 text-gray-600', 'icon' => '🏁'],
                    default     => ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-600', 'icon' => ''],
                };

                $canCancel = in_array($booking->status, ['Pending', 'Confirmed'])
                          && $booking->timeSlot->date >= today();
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Статус</p>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold {{ $statusConfig['class'] }}">
                            {{ $statusConfig['icon'] }} {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    @if ($booking->status === 'Pending')
                        <p class="text-xs text-gray-400 max-w-xs text-right">
                            Администратор подтвердит бронь в ближайшее время
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
                <h3 class="font-semibold text-gray-800 mb-4 text-sm uppercase tracking-wider text-gray-500">
                    Заезд
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400">Трасса</p>
                        <p class="font-semibold text-gray-800">{{ $booking->timeSlot->track->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Сложность</p>
                        <p class="font-semibold
                            @if($booking->timeSlot->track->difficulty === 'Easy') text-green-600
                            @elseif($booking->timeSlot->track->difficulty === 'Medium') text-yellow-600
                            @else text-red-600 @endif">
                            {{ match($booking->timeSlot->track->difficulty) {
                                'Easy' => 'Лёгкая',
                                'Medium' => 'Средняя',
                                'Hard' => 'Сложная',
                                default => $booking->timeSlot->track->difficulty
                            } }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Дата</p>
                        <p class="font-semibold text-gray-800">
                            {{ Carbon::parse($booking->timeSlot->date)->translatedFormat('d F Y, l') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Время</p>
                        <p class="font-semibold text-gray-800">
                            {{ Carbon::parse($booking->timeSlot->start_time)->format('H:i') }}
                            –
                            {{ Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Участников</p>
                        <p class="font-semibold text-gray-800">{{ $booking->participants_count }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Длина трассы</p>
                        <p class="font-semibold text-gray-800">{{ $booking->timeSlot->track->length }} м</p>
                    </div>
                </div>
            </div>

            @if ($booking->bookingKarts->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
                    <h3 class="font-semibold text-sm uppercase tracking-wider text-gray-500 mb-4">
                        Карты
                    </h3>
                    <div class="space-y-2">
                        @foreach ($booking->bookingKarts as $bk)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $bk->kartType->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $bk->kartType->seats }} {{ $bk->kartType->seats === 1 ? 'место' : 'места' }}
                                        · коэф. {{ $bk->kartType->price_modifier }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-700">× {{ $bk->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-5 mb-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-indigo-500">Итоговая стоимость</p>
                    <p class="text-3xl font-bold text-indigo-700">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} ₽
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
                <h3 class="font-semibold text-sm uppercase tracking-wider text-gray-500 mb-3">
                    Информация о записи
                </h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-400">Создано</p>
                        <p class="text-gray-700">{{ $booking->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Номер брони</p>
                        <p class="text-gray-700 font-mono">#{{ $booking->id }}</p>
                    </div>
                    @if ($booking->creator && $booking->creator->id !== $booking->user_id)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400">Запись создана администратором</p>
                            <p class="text-gray-700">{{ $booking->creator->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('bookings.index') }}"
                   class="flex-1 text-center px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    К списку броней
                </a>
                @if ($canCancel)
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?')"
                          class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full px-4 py-2.5 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition">
                            Отменить бронь
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
