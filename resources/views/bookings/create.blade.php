@php use Carbon\Carbon; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Бронирование заезда
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                    <h3 class="font-semibold text-red-700 mb-2">Исправьте ошибки:</h3>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Выбранный слот
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Трасса</p>
                        <p class="font-semibold text-gray-800">{{ $slot->track->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Дата</p>
                        <p class="font-semibold text-gray-800">
                            {{ Carbon::parse($slot->date)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Время</p>
                        <p class="font-semibold text-gray-800">
                            {{ Carbon::parse($slot->start_time)->format('H:i') }}
                            –
                            {{ Carbon::parse($slot->end_time)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Базовая цена</p>
                        <p class="font-semibold text-indigo-600">{{ number_format($slot->track->price_per_slot, 0, ',', ' ') }}
                            ₽</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-3 text-xs text-gray-500">
                    <span>Сложность:
                        <span class="font-medium
                            @if($slot->track->difficulty === 'Easy') text-green-600
                            @elseif($slot->track->difficulty === 'Medium') text-yellow-600
                            @else text-red-600 @endif">
                            {{ match($slot->track->difficulty) {
                                'Easy' => 'Лёгкая',
                                'Medium' => 'Средняя',
                                'Hard' => 'Сложная',
                                default => $slot->track->difficulty
                            } }}
                        </span>
                    </span>
                    <span>Макс. участников: <span
                            class="font-medium text-gray-700">{{ $slot->track->max_participants }}</span></span>
                    <span>Длина трассы: <span
                            class="font-medium text-gray-700">{{ $slot->track->length }} м</span></span>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('bookings.store') }}"
                x-data="{
                    participants: {{ old('participants_count', 1) }},
                    quantities: {{ Js::from($kartTypes->mapWithKeys(fn($k) => [$k->id => 0])) }},
                    kartTypes: {{ Js::from($kartTypes->map(fn($k) => [
                        'id' => $k->id,
                        'name' => $k->name,
                        'price_modifier' => (float) $k->price_modifier,
                        'seats' => $k->seats,
                        'min_age' => $k->min_age,
                        'max_age' => $k->max_age,
                        'min_height' => $k->min_height,
                    ])) }},
                    basePrice: {{ (float) $slot->track->price_per_slot }},
                    get total() {
                        return Object.entries(this.quantities).reduce((sum, [id, qty]) => {
                            const type = this.kartTypes.find(t => t.id == id);
                            if (!type || qty <= 0) return sum;
                            return sum + this.basePrice * type.price_modifier * qty;
                        }, 0);
                    },
                    get totalSeats() {
                        return Object.entries(this.quantities).reduce((sum, [id, qty]) => {
                            const type = this.kartTypes.find(t => t.id == id);
                            if (!type || qty <= 0) return sum;
                            return sum + type.seats * qty;
                        }, 0);
                    },
                    get seatsOk() {
                        return this.totalSeats >= this.participants;
                    }
                }"
            >
                @csrf
                <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Количество участников
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                    @click="if(participants > 1) participants--"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                <span class="text-lg leading-none">−</span>
                            </button>
                            <input type="number" name="participants_count"
                                   x-model.number="participants"
                                   min="1" max="{{ $slot->track->max_participants }}"
                                   class="w-20 text-center rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="button"
                                    @click="if(participants < {{ $slot->track->max_participants }}) participants++"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                <span class="text-lg leading-none">+</span>
                            </button>
                            <span class="text-sm text-gray-500">
                                (макс. {{ $slot->track->max_participants }})
                            </span>
                        </div>
                        @error('participants_count')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">
                            Выбор картов
                            <span class="text-red-500">*</span>
                        </h4>
                        @error('karts')
                        <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="space-y-3">
                            @foreach ($kartTypes as $i => $kartType)
                                <div
                                    class="rounded-lg border border-gray-200 p-4 flex flex-col sm:flex-row sm:items-center gap-3">

                                    <input type="hidden" name="karts[{{ $i }}][kart_type_id]"
                                           value="{{ $kartType->id }}">
                                    <input type="hidden" name="karts[{{ $i }}][quantity]"
                                           x-bind:value="quantities[{{ $kartType->id }}] ?? 0">

                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ $kartType->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $kartType->seats }} {{ $kartType->seats === 1 ? 'место' : 'места' }}
                                            · от {{ $kartType->min_age }} лет
                                            @if ($kartType->max_age)
                                                до {{ $kartType->max_age }} лет
                                            @endif
                                            · рост от {{ $kartType->min_height }} см
                                        </p>
                                        <p class="text-xs text-indigo-600 font-medium mt-0.5">
                                            {{ number_format($slot->track->price_per_slot * $kartType->price_modifier, 0, ',', ' ') }}
                                            ₽/карт
                                            <span class="text-gray-400">(коэф. {{ $kartType->price_modifier }})</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="if((quantities[{{ $kartType->id }}] ?? 0) > 0) quantities[{{ $kartType->id }}]--"
                                                class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition text-sm">
                                            −
                                        </button>
                                        <span class="w-8 text-center font-semibold text-gray-800"
                                              x-text="quantities[{{ $kartType->id }}] ?? 0"></span>
                                        <button type="button"
                                                @click="quantities[{{ $kartType->id }}] = (quantities[{{ $kartType->id }}] ?? 0) + 1"
                                                class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition text-sm">
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right min-w-[80px]">
                                        <p class="text-sm font-semibold text-gray-700"
                                           x-text="((quantities[{{ $kartType->id }}] ?? 0) * {{ (float) $slot->track->price_per_slot * (float) $kartType->price_modifier }}).toLocaleString('ru-RU') + ' ₽'">
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3 text-sm" x-show="Object.values(quantities).some(q => q > 0)">
                            <span class="text-gray-600">
                                Мест в выбранных картах:
                                <span class="font-semibold" x-text="totalSeats"></span>
                            </span>
                            <span x-show="!seatsOk" class="ml-2 text-red-600 text-xs font-medium">
                                — недостаточно для <span x-text="participants"></span>
                                {{ Str::plural('участника', 3) }}
                            </span>
                            <span x-show="seatsOk" class="ml-2 text-green-600 text-xs font-medium">
                                — достаточно ✓
                            </span>
                        </div>
                    </div>

                    <div
                        class="border-t border-gray-100 pt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Итоговая стоимость</p>
                            <p class="text-3xl font-bold text-indigo-600"
                               x-text="total.toLocaleString('ru-RU') + ' ₽'"></p>
                            <p class="text-xs text-gray-400 mt-0.5">Бронь создаётся со статусом «Ожидает
                                подтверждения»</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('schedule.index') }}"
                               class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                                Назад
                            </a>
                            <button type="submit"
                                    x-bind:disabled="total <= 0 || !seatsOk"
                                    x-bind:class="total > 0 && seatsOk
                                    ? 'bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer'
                                    : 'bg-gray-300 text-gray-500 cursor-not-allowed pointer-events-none'"
                                    class="px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                                Забронировать
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>
