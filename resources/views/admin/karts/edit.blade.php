<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование карта #{{ $kart->number }}</h1>

    <div class="bg-white p-6 rounded shadow-md max-w-2xl">
        <form action="{{ route('admin.karts.update', $kart->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="number">Номер карта</label>
                <input type="text" name="number" id="number" value="{{ old('number', $kart->number) }}" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('number') border-red-500 @enderror">
                @error('number')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type_id">Тип карта</label>
                <select name="type_id" id="type_id" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type_id') border-red-500 @enderror">
                    <option value="">Выберите тип</option>
                    @foreach ($kartTypes as $type)
                        <option value="{{ $type->id }}" {{ old('type_id', $kart->type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} (Мест: {{ $type->seats }})
                        </option>
                    @endforeach
                </select>
                @error('type_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Статус</label>
                <select name="status" id="status" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption->value }}" {{ old('status', $kart->status->value) == $statusOption->value ? 'selected' : '' }}>
                            {{ $statusOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.karts.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>