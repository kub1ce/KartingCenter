<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Управление слотами</h1>

    <div class="bg-white p-4 rounded shadow-md mb-6">
        <form action="{{ route('admin.slots.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Трасса</label>
                <select name="track_id" class="border rounded py-2 px-3 text-gray-700">
                    <option value="">Все</option>
                    @foreach ($tracks as $track)
                        <option value="{{ $track->id }}" {{ request('track_id') == $track->id ? 'selected' : '' }}>{{ $track->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Дата</label>
                <input type="date" name="date" value="{{ request('date') }}" class="border rounded py-2 px-3 text-gray-700">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Фильтр</button>
            <a href="{{ route('admin.slots.index') }}" class="text-gray-600 hover:text-gray-800">Сбросить</a>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-md rounded">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Дата / Время</th>
                    <th class="py-3 px-6 text-left">Трасса</th>
                    <th class="py-3 px-6 text-center">Статус</th>
                    <th class="py-3 px-6 text-center">Действия</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($slots as $slot)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">
                        {{ $slot->date }} <br>
                        <span class="text-xs text-gray-500">{{ $slot->start_time }} - {{ $slot->end_time }}</span>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $slot->track->name ?? '—' }}</td>
                    <td class="py-3 px-6 text-center">
                        @if($slot->is_blocked)
                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Заблокирован</span>
                        @else
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Свободен</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('admin.slots.edit', $slot->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Изменить</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $slots->links() }}
    </div>
</div>
</x-admin-layout>