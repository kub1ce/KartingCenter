@php use Carbon\Carbon; @endphp
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-4xl mx-auto">

    <a href="{{ route('schedule.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-6 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Назад к расписанию
    </a>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-900/30 border border-red-500/50 p-4 backdrop-blur-sm">
            <h3 class="font-bold text-red-400 mb-2 uppercase tracking-wider text-sm">Ошибка бронирования:</h3>
            <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $trackColors = [
            'Easy' => ['text' => 'text-lime-400', 'bg' => 'bg-lime-500/20', 'border' => 'border-lime-500/50', 'label' => 'EASY', 'stroke_base' => '#052e16', 'stroke_glow' => '#a3e635'],
            'Medium' => ['text' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50', 'label' => 'MEDIUM', 'stroke_base' => '#422006', 'stroke_glow' => '#facc15'],
            'Hard' => ['text' => 'text-red-400', 'bg' => 'bg-red-500/20', 'border' => 'border-red-500/50', 'label' => 'HARD', 'stroke_base' => '#450a0a', 'stroke_glow' => '#ef4444'],
        ];
        $c = $trackColors[$slot->track->difficulty->name] ?? $trackColors['Easy'];
        $svgPath = $slot->track->svg_code ?? "M 50 200 Q 150 50 250 150 T 450 200 T 250 350 T 50 200";
    @endphp

    <div class="glass-card rounded-xl p-6 md:p-8 mb-6 border-l-4 {{ $c['border'] }} relative overflow-hidden">
        <div class="absolute top-0 right-0 w-2/3 h-full opacity-20 pointer-events-none overflow-hidden flex justify-end items-center perspective-1000">
            <div class="transform-3d w-[600px] h-[600px]">
                <svg viewBox="0 0 500 400" class="w-full h-full">
                    <path d="{{ $svgPath }}" fill="none" stroke-linecap="square" stroke-linejoin="miter" stroke-width="30" stroke="{{ $c['stroke_base'] }}" />
                    <path d="{{ $svgPath }}" fill="none" stroke-linecap="square" stroke-linejoin="miter" stroke-width="30" stroke="{{ $c['stroke_glow'] }}" 
                          style="filter: drop-shadow(0 0 8px {{ $c['stroke_glow'] }}); stroke-dasharray: 100 800; animation: raceGlow 4s linear infinite;" />
                </svg>
            </div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="{{ $c['bg'] }} border {{ $c['border'] }} {{ $c['text'] }} text-[10px] font-black px-2.5 py-1 rounded tracking-widest uppercase">
                    {{ $c['label'] }}
                </span>
                <h2 class="text-2xl font-black text-white uppercase tracking-wide">{{ $slot->track->name }}</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Дата</p>
                    <p class="font-bold text-white">{{ Carbon::parse($slot->date)->isoFormat('D MMMM') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Время</p>
                    <p class="font-bold text-white font-mono tracking-wider">{{ Carbon::parse($slot->start_time)->format('H:i') }} – {{ Carbon::parse($slot->end_time)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Длина</p>
                    <p class="font-bold text-white">{{ $slot->track->length }} м</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Базовая цена</p>
                    <p class="font-bold {{ $c['text'] }}">{{ number_format($slot->track->price_per_slot, 0, ',', ' ') }} ₽</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('bookings.store') }}"
          x-data="{
                participants: {{ old('participants_count', 1) }},
                quantities: {{ Js::from($kartTypes->mapWithKeys(fn($k) => [$k->id => 0])) }},
                limits: {{ Js::from($kartLimits) }},
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
            }">
        @csrf
        <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">

        <div class="glass-card rounded-xl p-6 md:p-8 space-y-8">

            <div>
                <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">
                    Количество участников
                </label>
                <div class="flex items-center gap-4">
                    <button type="button" @click="if(participants > 1) participants--"
                            class="w-12 h-12 flex items-center justify-center rounded-md border border-gray-700 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition text-2xl font-black">
                        -
                    </button>
                    <input type="number" name="participants_count" x-model.number="participants"
                           min="1" max="{{ $slot->track->max_participants }}"
                           class="w-20 text-center bg-transparent border-b-2 border-gray-700 text-white text-3xl font-black font-mono focus:outline-none focus:border-lime-500 transition">
                    <button type="button" @click="if(participants < {{ $slot->track->max_participants }}) participants++"
                            class="w-12 h-12 flex items-center justify-center rounded-md border border-gray-700 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition text-2xl font-black">
                        +
                    </button>
                    <span class="text-xs text-gray-600 uppercase tracking-widest">
                        макс. {{ $slot->track->max_participants }}
                    </span>
                </div>
                @error('participants_count')
                <p class="mt-2 text-xs text-red-400 font-bold uppercase">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">
                    Выбор картов
                </h4>
                @error('karts')
                <p class="mb-3 text-xs text-red-400 font-bold uppercase bg-red-900/20 border border-red-500/30 p-2 rounded">{{ $message }}</p>
                @enderror

                <div class="space-y-4">
                    @foreach ($kartTypes as $i => $kartType)
                        <div class="bg-black/30 rounded-lg border border-white/5 p-4 flex flex-col sm:flex-row sm:items-center gap-4 transition-all duration-300"
                             x-bind:class="(quantities[{{ $kartType->id }}] ?? 0) > 0 ? 'border-lime-500/30 shadow-[0_0_10px_rgba(163,230,53,0.1)]' : ''">

                            <input type="hidden" name="karts[{{ $i }}][kart_type_id]" value="{{ $kartType->id }}">
                            <input type="hidden" name="karts[{{ $i }}][quantity]" x-bind:value="quantities[{{ $kartType->id }}] ?? 0">

                            <div class="flex-1">
                                <p class="font-black text-white uppercase tracking-wide">{{ $kartType->name }}</p>
                                <p class="text-xs text-gray-500 mt-1 font-mono">
                                    {{ $kartType->seats }} {{ $kartType->seats === 1 ? 'место' : 'места' }} · 
                                    от {{ $kartType->min_age }} лет
                                    @if ($kartType->max_age) до {{ $kartType->max_age }} лет @endif · 
                                    рост от {{ $kartType->min_height }} см
                                </p>
                                <p class="text-sm font-bold text-lime-400 mt-1">
                                    {{ number_format($slot->track->price_per_slot * $kartType->price_modifier, 0, ',', ' ') }} ₽/карт
                                    <span class="text-[10px] text-gray-600 font-normal">(коэф. {{ $kartType->price_modifier }})</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" x-bind:disabled="(quantities[{{ $kartType->id }}] ?? 0) <= 0"
                                        @click="if((quantities[{{ $kartType->id }}] ?? 0) > 0) quantities[{{ $kartType->id }}]--"
                                        class="w-10 h-10 flex items-center justify-center rounded border border-gray-700 text-gray-400 hover:border-white hover:text-white transition disabled:opacity-20 disabled:cursor-not-allowed font-black text-lg">
                                    -
                                </button>
                                <span class="w-8 text-center text-2xl font-black text-white font-mono" x-text="quantities[{{ $kartType->id }}] ?? 0"></span>
                                <button type="button" x-bind:disabled="(quantities[{{ $kartType->id }}] ?? 0) >= limits[{{ $kartType->id }}]['max']"
                                        @click="if((quantities[{{ $kartType->id }}] ?? 0) < limits[{{ $kartType->id }}]['max']) quantities[{{ $kartType->id }}]++"
                                        class="w-10 h-10 flex items-center justify-center rounded border border-gray-700 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition disabled:opacity-20 disabled:cursor-not-allowed font-black text-lg">
                                    +
                                </button>
                            </div>

                            <div class="text-right min-w-[100px] hidden sm:block">
                                <p class="text-lg font-black text-white font-mono"
                                   x-text="((quantities[{{ $kartType->id }}] ?? 0) * {{ (float) $slot->track->price_per_slot * (float) $kartType->price_modifier }}).toLocaleString('ru-RU') + ' ₽'">
                                </p>
                            </div>
                        </div>

                        <div class="mt-2 text-xs font-bold text-orange-400 bg-orange-900/20 border border-orange-500/30 rounded px-3 py-2 flex items-center gap-2"
                                x-show="limits[{{ $kartType->id }}]['showWarning'] === true && (quantities[{{ $kartType->id }}] ?? 0) === limits[{{ $kartType->id }}]['max']">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>Остальные карты этого типа заняты в пересекающихся заездах.</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-sm font-bold uppercase tracking-wider" x-show="Object.values(quantities).some(q => q > 0)">
                    <span class="text-gray-500">
                        Мест в картах: <span class="text-white font-mono" x-text="totalSeats"></span>
                    </span>
                    <span x-show="!seatsOk" class="ml-2 text-red-400 text-xs">
                        (Недостаточно для <span x-text="participants"></span> уч.)
                    </span>
                    <span x-show="seatsOk" class="ml-2 text-lime-400 text-xs">
                        ✓ Хватит всем
                    </span>
                </div>
            </div>

            <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="text-center sm:text-left w-full sm:w-auto">
                    <p class="text-xs text-gray-600 uppercase tracking-widest mb-1">Итого к оплате</p>
                    <p class="text-4xl font-black text-lime-400 font-mono leading-none"
                        style="text-shadow: 0 0 10px rgba(163, 230, 53, 0.6), 0 0 20px rgba(163, 230, 53, 0.4);"
                        x-text="total.toLocaleString('ru-RU') + ' ₽'"></p>
                    <p class="text-[10px] text-gray-600 mt-2 uppercase tracking-widest">Статус: «Ожидает подтверждения»</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('schedule.index') }}"
                       class="flex-1 sm:flex-none text-center px-6 py-3 border border-gray-700 text-gray-500 hover:text-white hover:border-white text-sm font-black uppercase tracking-widest transition">
                        Отмена
                    </a>
                    <button type="submit"
                            x-bind:disabled="total <= 0 || !seatsOk"
                            x-bind:class="total > 0 && seatsOk
                            ? 'bg-lime-500 hover:bg-lime-400 text-black shadow-[0_0_25px_rgba(163,230,53,0.4)]'
                            : 'bg-gray-800 text-gray-600 cursor-not-allowed'"
                            class="flex-1 sm:flex-none px-8 py-3 font-black uppercase tracking-widest text-sm transition disabled:shadow-none">
                        Забронировать
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
</x-app-layout>