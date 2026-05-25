<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование акции</h1>

    <div class="bg-white p-6 rounded shadow-md max-w-2xl">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Название акции</label>
                <input type="text" name="title" id="title" value="{{ old('title', $promotion->title) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Описание</label>
                <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $promotion->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="discount_percent">Процент скидки</label>
                <input type="number" name="discount_percent" id="discount_percent" min="1" max="99" value="{{ old('discount_percent', $promotion->discount_percent) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('discount_percent')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Начало</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('start_date')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">Окончание</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('end_date')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox h-5 w-5 text-blue-600" @checked(old('is_active', $promotion->is_active))>
                    <span class="ml-2 text-gray-700 text-sm font-bold">Активна</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.promotions.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>