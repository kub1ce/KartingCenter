<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Управление слотом #{{ $slot->id }}</h1>
    <div class="bg-white p-6 rounded shadow-md max-w-md">
        <p class="mb-2 text-gray-700"><strong>Трасса:</strong> {{ $slot->track->name ?? '—' }}</p>
        <p class="mb-4 text-gray-700"><strong>Время:</strong> {{ $slot->date }} {{ $slot->start_time }} - {{ $slot->end_time }}</p>
        
        <form action="{{ route('admin.slots.update', $slot->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_blocked" value="1" class="form-checkbox h-5 w-5 text-red-600" @checked($slot->is_blocked)>
                    <span class="ml-2 text-gray-700 text-sm font-bold">Заблокировать слот (Техобслуживание/Мероприятие)</span>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
                <a href="{{ route('admin.slots.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>