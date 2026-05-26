<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование трассы: {{ $track->name }}</h1>
    <div class="bg-white p-6 rounded shadow-md max-w-2xl">
        <form action="{{ route('admin.tracks.update', $track->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Название</label>
                <input type="text" name="name" id="name" value="{{ old('name', $track->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Описание</label>
                <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('description', $track->description) }}</textarea>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="length">Длина (м)</label>
                    <input type="number" name="length" id="length" value="{{ old('length', $track->length) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="difficulty">Сложность</label>
                    <select name="difficulty" id="difficulty" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        @foreach ($difficulties as $diff)
                            <option value="{{ $diff->value }}" {{ old('difficulty', $track->difficulty->value) == $diff->value ? 'selected' : '' }}>{{ $diff->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-4 mb-6">
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="max_participants">Макс. участников</label>
                    <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $track->max_participants) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price_per_slot">Цена за слот (₽)</label>
                    <input type="number" name="price_per_slot" id="price_per_slot" step="0.01" value="{{ old('price_per_slot', $track->price_per_slot) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
                <a href="{{ route('admin.tracks.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>