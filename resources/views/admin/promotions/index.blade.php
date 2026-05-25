<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Акции и скидки</h1>
        <a href="{{ route('admin.promotions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Добавить акцию
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Название</th>
                    <th class="py-3 px-6 text-center">Скидка</th>
                    <th class="py-3 px-6 text-center">Период действия</th>
                    <th class="py-3 px-6 text-center">Статус</th>
                    <th class="py-3 px-6 text-center">Действия</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($promotions as $item)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        {{ $item->title }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">-{{ $item->discount_percent }}%</span>
                    </td>
                    <td class="py-3 px-6 text-center text-xs">
                        {{ $item->start_date->format('d.m.Y') }} — {{ $item->end_date->format('d.m.Y') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        @if($item->is_active)
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Активна</span>
                        @else
                            <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs">Выключена</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('admin.promotions.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Ред.</a>
                            <form action="{{ route('admin.promotions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Удалить акцию?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs ml-2">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $promotions->links() }}
    </div>
</div>
</x-admin-layout>