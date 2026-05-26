<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Создание бронирования (по звонку)</h1>

    <div class="bg-white p-6 rounded shadow-md max-w-2xl">
        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="user_id">Клиент</label>
                <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">Выберите клиента</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="time_slot_id">Временной слот</label>
                <select name="time_slot_id" id="time_slot_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">Выберите слот</option>
                    @foreach ($freeSlots as $slot)
                        <option value="{{ $slot->id }}" {{ old('time_slot_id') == $slot->id ? 'selected' : '' }}>
                            {{ $slot->track->name ?? 'Трасса?' }} | {{ $slot->date }} ({{ $slot->start_time }} - {{ $slot->end_time }})
                        </option>
                    @endforeach
                </select>
                @error('time_slot_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="participants_count">Количество участников</label>
                <input type="number" name="participants_count" id="participants_count" min="1" value="{{ old('participants_count', 1) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                @error('participants_count')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Забронировать
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>