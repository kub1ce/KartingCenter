<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Акции и скидки</h1>

    @if($promotions->isEmpty())
        <div class="text-center py-12 text-gray-500">
            На данный момент активных акций нет. Следите за обновлениями!
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promotions as $promo)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                    <div class="bg-red-600 text-white text-center py-4 font-bold text-2xl">
                        Скидка {{ $promo->discount_percent }}%
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="font-bold text-gray-900 text-xl mb-2">{{ $promo->title }}</h2>
                        <p class="text-gray-600 text-sm flex-1 mb-4">{{ $promo->description }}</p>
                        <div class="mt-auto border-t border-gray-100 pt-4 text-sm text-gray-500">
                            <p>Период действия:</p>
                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($promo->start_date)->format('d.m.Y') }} — {{ \Carbon\Carbon::parse($promo->end_date)->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $promotions->links() }}</div>
    @endif>
</div>
</x-app-layout>