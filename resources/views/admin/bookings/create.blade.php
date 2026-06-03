<x-admin-layout>
<div>
    <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-6 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Все бронирования
    </a>

    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-yellow-500 pl-4 mb-8">Ручное бронирование</h1>

    <div class="glass-card p-6 rounded-xl max-w-4xl">
        <form action="{{ route('admin.bookings.store') }}" method="POST" id="bookingForm"
              x-data="{
                    participants: {{ old('participants_count', 1) }},
                    quantities: {{ Js::from($kartTypes->mapWithKeys(fn($k) => [$k->id => 0])) }},
                    limits: {},
                    kartTypes: {{ Js::from($kartTypes->map(fn($k) => [
                        'id' => $k->id,
                        'name' => $k->name,
                        'price_modifier' => (float) $k->price_modifier,
                        'seats' => $k->seats,
                    ])) }},
                    basePrice: 0,
                    get total() {
                        if (!this.basePrice) return 0;
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
                    get seatsOk() { return this.totalSeats >= this.participants; },
                    
                    async fetchSlotDetails(slotId) {
                        if (!slotId) {
                            this.basePrice = 0;
                            this.limits = {};
                            return;
                        }
                        try {
                            const response = await fetch(`/admin/api/slot-details/${slotId}`);
                            const data = await response.json();
                            this.basePrice = data.basePrice;
                            this.limits = data.limits;
                            
                            this.quantities = Object.fromEntries(
                                Object.entries(this.quantities).map(([id, qty]) => {
                                    const max = this.limits[id]?.max ?? 0;
                                    return [id, qty > max ? max : qty];
                                })
                            );
                        } catch (e) {
                            this.basePrice = 0;
                            this.limits = {};
                        }
                    },
                    
                    init() {
                        const slotSelect = document.getElementById('slot_select');
                        if (slotSelect && slotSelect.value) {
                            this.fetchSlotDetails(slotSelect.value);
                        }
                    }
              }">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Клиент</label>
                <select name="user_id" id="user_id" required
                        class="appearance-none bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm w-full"
                        style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                    <option value="">Выберите клиента</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->phone ?? $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-400 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Дата заезда</label>
                    <input type="date" name="booking_date" id="booking_date" required value="{{ old('booking_date', $selectedSlot?->date->format('Y-m-d')) }}" 
                           class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono" style="color-scheme: dark;">
                </div>
                <div>
                    <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Трасса</label>
                    <select name="track_id" id="track_select" required
                            class="appearance-none bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm w-full disabled:opacity-30"
                            style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                        @if($selectedSlot)
                            <option value="{{ $selectedSlot->track->id }}" selected>{{ $selectedSlot->track->name }}</option>
                        @else
                            <option value="">Сначала выберите дату</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Время заезда</label>
                <select name="time_slot_id" id="slot_select" required
                        class="appearance-none bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm w-full disabled:opacity-30"
                        style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                    @if($selectedSlot)
                        <option value="{{ $selectedSlot->id }}" selected>{{ \Carbon\Carbon::parse($selectedSlot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($selectedSlot->end_time)->format('H:i') }}</option>
                    @else
                        <option value="">Сначала выберите трассу</option>
                    @endif
                </select>
                @error('time_slot_id') <p class="text-red-400 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-white/5 mb-6"></div>

            <div class="mb-6">
                <label class="block text-gray-500 text-[10px] font-bold mb-3 uppercase tracking-widest">Количество участников</label>
                <div class="flex items-center gap-4">
                    <button type="button" @click="if(participants > 1) participants--"
                            class="w-12 h-12 flex items-center justify-center rounded-md border border-white/10 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition text-2xl font-black">
                        -
                    </button>
                    <input type="number" name="participants_count" x-model.number="participants"
                           min="1" required
                           class="w-20 text-center bg-transparent border-b-2 border-white/10 text-white text-3xl font-black font-mono focus:outline-none focus:border-lime-500 transition">
                    <button type="button" @click="participants++"
                            class="w-12 h-12 flex items-center justify-center rounded-md border border-white/10 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition text-2xl font-black">
                        +
                    </button>
                </div>
                @error('participants_count') <p class="text-red-400 text-[10px] font-bold uppercase mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-gray-500 text-[10px] font-bold mb-4 uppercase tracking-widest">Выбор картов</label>
                @error('karts') <p class="mb-3 text-xs text-red-400 font-bold uppercase bg-red-900/20 border border-red-500/30 p-2 rounded">{{ $message }}</p> @enderror

                <div class="space-y-3">
                    @foreach ($kartTypes as $i => $kartType)
                        <div class="bg-black/30 rounded-lg border border-white/5 p-4 flex flex-col sm:flex-row sm:items-center gap-4 transition-all duration-300"
                             x-bind:class="(quantities[{{ $kartType->id }}] ?? 0) > 0 ? 'border-lime-500/30 shadow-[0_0_10px_rgba(163,230,53,0.1)]' : ''">

                            <input type="hidden" name="karts[{{ $i }}][kart_type_id]" value="{{ $kartType->id }}">
                            <input type="hidden" name="karts[{{ $i }}][quantity]" x-bind:value="quantities[{{ $kartType->id }}] ?? 0">

                            <div class="flex-1">
                                <p class="font-black text-white uppercase tracking-wide text-sm">{{ $kartType->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $kartType->seats }} {{ $kartType->seats === 1 ? 'место' : 'места' }} · Коэф: {{ $kartType->price_modifier }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" x-bind:disabled="(quantities[{{ $kartType->id }}] ?? 0) <= 0"
                                        @click="if((quantities[{{ $kartType->id }}] ?? 0) > 0) quantities[{{ $kartType->id }}]--"
                                        class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-gray-400 hover:border-white hover:text-white transition disabled:opacity-20 disabled:cursor-not-allowed font-black text-lg">
                                    -
                                </button>
                                <span class="w-8 text-center text-2xl font-black text-white font-mono" x-text="quantities[{{ $kartType->id }}] ?? 0"></span>
                                <button type="button" 
                                        x-bind:disabled="(quantities[{{ $kartType->id }}] ?? 0) >= (limits[{{ $kartType->id }}]?.max ?? 0)"
                                        @click="const max = limits[{{ $kartType->id }}]?.max ?? 0; if(max > 0 && (quantities[{{ $kartType->id }}] ?? 0) < max) quantities[{{ $kartType->id }}]++"
                                        class="w-10 h-10 flex items-center justify-center rounded border border-white/10 text-gray-400 hover:border-lime-500 hover:text-lime-400 transition disabled:opacity-20 disabled:cursor-not-allowed font-black text-lg">
                                    +
                                </button>
                                <span class="text-xs text-gray-600 font-mono w-16 text-right" x-text="'макс. ' + (limits[{{ $kartType->id }}]?.max ?? 0)"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-sm font-bold uppercase tracking-wider" x-show="Object.values(quantities).some(q => q > 0)">
                    <span class="text-gray-500">Мест в картах: <span class="text-white font-mono" x-text="totalSeats"></span></span>
                    <span x-show="!seatsOk" class="ml-2 text-red-400 text-xs">(Недостаточно)</span>
                    <span x-show="seatsOk" class="ml-2 text-lime-400 text-xs">✓ Хватит</span>
                </div>
            </div>

            <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="text-left w-full sm:w-auto">
                    <p class="text-xs text-gray-600 uppercase tracking-widest mb-1">Итого к оплате</p>
                    <p class="text-3xl font-black text-lime-400 font-mono leading-none"
                        style="text-shadow: 0 0 10px rgba(163, 230, 53, 0.6);"
                        x-text="total.toLocaleString('ru-RU') + ' ₽'"></p>
                    <p class="text-[10px] text-lime-600 mt-2 uppercase tracking-widest">Статус: «Подтверждена»</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.bookings.index') }}"
                       class="flex-1 sm:flex-none text-center px-6 py-3 border border-white/10 text-gray-500 hover:text-white hover:border-white text-sm font-black uppercase tracking-widest transition">
                        Отмена
                    </a>
                    <button type="submit"
                            x-bind:disabled="total <= 0 || !seatsOk"
                            x-bind:class="total > 0 && seatsOk
                            ? 'bg-yellow-500 hover:bg-yellow-400 text-black shadow-[0_0_15px_rgba(234,179,8,0.4)]'
                            : 'bg-gray-800 text-gray-600 cursor-not-allowed'"
                            class="flex-1 sm:flex-none px-8 py-3 font-black uppercase tracking-widest text-sm transition disabled:shadow-none">
                        Создать бронь
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date');
    const trackSelect = document.getElementById('track_select');
    const slotSelect = document.getElementById('slot_select');
    const alpineData = Alpine.$data(document.getElementById('bookingForm'));

    dateInput.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;
        trackSelect.innerHTML = '<option value="">Загрузка трасс...</option>';
        trackSelect.disabled = true;
        slotSelect.innerHTML = '<option value="">Сначала выберите трассу</option>';
        slotSelect.disabled = true;
        alpineData.fetchSlotDetails(null);

        fetch(`/admin/api/tracks-by-date?date=${date}`)
            .then(response => response.json())
            .then(data => {
                trackSelect.innerHTML = '<option value="">Выберите трассу</option>';
                data.forEach(track => {
                    const option = document.createElement('option');
                    option.value = track.id;
                    option.textContent = track.name;
                    trackSelect.appendChild(option);
                });
                trackSelect.disabled = false;
            });
    });

    trackSelect.addEventListener('change', function() {
        const trackId = this.value;
        const date = dateInput.value;
        if (!trackId || !date) return;

        slotSelect.innerHTML = '<option value="">Загрузка слотов...</option>';
        slotSelect.disabled = true;
        alpineData.fetchSlotDetails(null);

        fetch(`/admin/api/slots-by-track?date=${date}&track_id=${trackId}`)
            .then(response => response.json())
            .then(data => {
                slotSelect.innerHTML = '<option value="">Выберите время</option>';
                if (data.length === 0) {
                    slotSelect.innerHTML = '<option value="">Нет свободных слотов</option>';
                } else {
                    data.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.id;
                        option.textContent = slot.time_range;
                        slotSelect.appendChild(option);
                    });
                }
                slotSelect.disabled = false;
            });
    });

    slotSelect.addEventListener('change', function() {
        alpineData.fetchSlotDetails(this.value);
    });

    if (dateInput.value && !trackSelect.value) {
        dateInput.dispatchEvent(new Event('change'));
    }
});
</script>
</x-admin-layout>