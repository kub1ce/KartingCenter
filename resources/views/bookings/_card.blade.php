@php
    use App\Enums\BookingStatus;use Carbon\Carbon;

    $statusConfig = match($booking->status) {
        'Pending'   => ['label' => 'Ожидает подтверждения', 'class' => 'bg-yellow-100 text-yellow-700'],
        'Confirmed' => ['label' => 'Подтверждено', 'class' => 'bg-green-100 text-green-700'],
        'Cancelled' => ['label' => 'Отменено', 'class' => ' bg-red-100 text-red-700'],
        'Completed' => ['label' => 'Завершено', 'class' => 'bg-gray-100 text-gray-600'],
        default     => ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-600'],
    };

    $status = $booking->status instanceof BookingStatus
    ? $booking->status
    : BookingStatus::from($booking->status);

    $canCancel = in_array($status, [BookingStatus::Pending, BookingStatus::Confirmed])
          && Carbon::parse($booking->timeSlot->date)->format('Y-m-d') >= today()->format('Y-m-d');
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-semibold text-gray-800 text-base">
                        {{ $booking->timeSlot->track->name }}
                    </h3>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ Carbon::parse($booking->timeSlot->date)->translatedFormat('d F Y') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ Carbon::parse($booking->timeSlot->start_time)->format('H:i') }}
                        –
                        {{ Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $booking->participants_count }}
                        {{ trans_choice('участник|участника|участников', $booking->participants_count) }}
                    </span>
                </div>

                @if ($booking->bookingKarts->count() > 0)
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach ($booking->bookingKarts as $bk)
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs">
                                {{ $bk->kartType->name }} × {{ $bk->quantity }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2">
                <p class="text-xl font-bold text-indigo-600">
                    {{ number_format($booking->total_price, 0, ',', ' ') }} ₽
                </p>
                <div class="flex gap-2">
                    <a href="{{ route('bookings.show', $booking) }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Подробнее
                    </a>
                    @if ($canCancel)
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Отменить бронирование?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium rounded-md border border-red-200 text-red-600 hover:bg-red-50 transition">
                                Отменить
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="px-5 py-2 bg-gray-50 border-t border-gray-100 flex justify-between text-xs text-gray-400">
        <span>#{{ $booking->id }}</span>
        <span>Создано: {{ $booking->created_at->translatedFormat('d M Y, H:i') }}</span>
    </div>
</div>
