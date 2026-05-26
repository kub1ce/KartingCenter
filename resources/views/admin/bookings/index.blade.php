<x-admin-layout>
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Управление бронированиями</h1>
        <a href="{{ route('admin.bookings.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            + Создать бронь
        </a>
    </div>

    <!-- Фильтры -->
    <div class="bg-white p-4 rounded shadow-md mb-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Статус</label>
                <select name="status" class="border rounded py-2 px-3 text-gray-700">
                    <option value="">Все</option>
                    @foreach ($statuses as $statusOpt)
                        <option value="{{ $statusOpt->value }}" {{ request('status') == $statusOpt->value ? 'selected' : '' }}>{{ $statusOpt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Дата</label>
                <input type="date" name="date" value="{{ request('date') }}" class="border rounded py-2 px-3 text-gray-700">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Трасса</label>
                <select name="track_id" class="border rounded py-2 px-3 text-gray-700">
                    <option value="">Все</option>
                    @foreach ($tracks as $track)
                        <option value="{{ $track->id }}" {{ request('track_id') == $track->id ? 'selected' : '' }}>{{ $track->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Фильтр</button>
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800">Сбросить</a>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-md rounded">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Клиент</th>
                    <th class="py-3 px-6 text-left">Трасса / Слот</th>
                    <th class="py-3 px-6 text-center">Участники</th>
                    <th class="py-3 px-6 text-center">Статус</th>
                    <th class="py-3 px-6 text-center">Действия</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($bookings as $booking)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">#{{ $booking->id }}</td>
                    <td class="py-3 px-6 text-left">{{ $booking->user->name ?? 'Удален' }}</td>
                    <td class="py-3 px-6 text-left">
                        {{ $booking->timeSlot->track->name ?? '—' }}<br>
                        <span class="text-xs text-gray-500">{{ $booking->timeSlot->date }} {{ $booking->timeSlot->start_time }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">{{ $booking->participants_count }}</td>
                    <td class="py-3 px-6 text-center">
                        @php
                            $colorClass = 'bg-gray-200 text-gray-600';
                            if ($booking->status == \App\Enums\BookingStatus::Pending) $colorClass = 'bg-yellow-200 text-yellow-600';
                            if ($booking->status == \App\Enums\BookingStatus::Confirmed) $colorClass = 'bg-green-200 text-green-600';
                            if ($booking->status == \App\Enums\BookingStatus::Cancelled) $colorClass = 'bg-red-200 text-red-600';
                            if ($booking->status == \App\Enums\BookingStatus::Completed) $colorClass = 'bg-blue-200 text-blue-600';
                        @endphp
                        <span class="{{ $colorClass }} py-1 px-3 rounded-full text-xs">
                            {{ $booking->status->name }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            @if($booking->status == \App\Enums\BookingStatus::Pending)
                                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs mr-1">Подтвердить</button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Отклонить</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Обработано</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
</x-admin-layout>