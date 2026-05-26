<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Управление трассами</h1>
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Название</th>
                    <th class="py-3 px-6 text-center">Длина (м)</th>
                    <th class="py-3 px-6 text-center">Сложность</th>
                    <th class="py-3 px-6 text-center">Цена за слот</th>
                    <th class="py-3 px-6 text-center">Действия</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($tracks as $track)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $track->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $track->length }}</td>
                    <td class="py-3 px-6 text-center">{{ $track->difficulty->name }}</td>
                    <td class="py-3 px-6 text-center">{{ $track->price_per_slot }} ₽</td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('admin.tracks.edit', $track->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Ред.</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>