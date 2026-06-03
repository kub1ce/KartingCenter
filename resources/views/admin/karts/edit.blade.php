<x-admin-layout>
<div>
    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-yellow-500 pl-4 mb-8">Редактирование карта #{{ $kart->number }}</h1>

    <div class="glass-card p-6 rounded-xl max-w-2xl" x-data="{ 
        typeOpen: false, selectedTypeId: '{{ old('kart_type_id', $kart->kart_type_id) }}',
        statusOpen: false, selectedStatus: '{{ old('status', $kart->status->value) }}'
    }">
        
        @if(session('warning'))
            <div class="mb-6 bg-amber-900/20 border border-amber-500/30 p-4 rounded-lg text-amber-400 backdrop-blur-sm">
                <p class="font-black uppercase tracking-widest text-xs mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    Системное предупреждение
                </p>
                <p class="text-sm mb-3">{{ session('warning') }}</p>
                <form action="{{ route('admin.karts.update', $kart->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="number" value="{{ old('number', $kart->number) }}">
                    <input type="hidden" name="kart_type_id" value="{{ old('kart_type_id', $kart->kart_type_id) }}">
                    <input type="hidden" name="status" value="{{ \App\Enums\KartStatus::Maintenance->value }}">
                    <input type="hidden" name="force_maintenance" value="1">
                    <button type="submit" class="bg-red-500 hover:bg-red-400 text-white font-black py-1.5 px-4 uppercase text-[10px] tracking-widest transition hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                        Всё равно поставить на ТО
                    </button>
                </form>
            </div>
        @endif

        <form action="{{ route('admin.karts.update', $kart->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="number">Номер карта</label>
                <input type="text" name="number" id="number" value="{{ old('number', $kart->number) }}" 
                       class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono font-bold">
                @error('number') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5 relative">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Тип карта</label>
                <input type="hidden" name="kart_type_id" x-model="selectedTypeId">
                
                <button type="button" @click="typeOpen = !typeOpen" 
                        class="w-full bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm text-left flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        @foreach ($kartTypes as $type)
                            <span x-show="selectedTypeId == '{{ $type->id }}'">{{ $type->name }} (Мест: {{ $type->seats }})</span>
                        @endforeach
                    </span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform" :class="typeOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="typeOpen" @click.away="typeOpen = false" 
                     class="absolute z-10 mt-1 w-full bg-black/95 border border-white/10 rounded-md shadow-xl backdrop-blur-xl py-1"
                     x-transition>
                    @foreach ($kartTypes as $type)
                        <button type="button" 
                                class="w-full text-left px-4 py-2.5 text-sm flex items-center gap-2 transition"
                                :class="selectedTypeId == '{{ $type->id }}' ? 'bg-lime-500/20 text-lime-400' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                                @click="selectedTypeId = '{{ $type->id }}'; typeOpen = false">
                            <span class="w-2 h-2 rounded-full bg-lime-500"></span> {{ $type->name }} (Мест: {{ $type->seats }})
                        </button>
                    @endforeach
                </div>
                @error('kart_type_id') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6 relative">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Статус</label>
                <input type="hidden" name="status" x-model="selectedStatus">
                
                <button type="button" @click="statusOpen = !statusOpen"
                        class="w-full bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm text-left flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" :class="selectedStatus === 'Available' ? 'bg-lime-500' : (selectedStatus === 'Reserved' ? 'bg-yellow-500' : 'bg-red-500')"></span>
                        <span x-text="selectedStatus"></span>
                    </span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform" :class="statusOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="statusOpen" @click.away="statusOpen = false" 
                     class="absolute z-10 mt-1 w-full bg-black/95 border border-white/10 rounded-md shadow-xl backdrop-blur-xl py-1"
                     x-transition>
                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm flex items-center gap-2 transition"
                            :class="selectedStatus === 'Available' ? 'bg-lime-500/20 text-lime-400' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                            @click="selectedStatus = 'Available'; statusOpen = false">
                        <span class="w-2 h-2 rounded-full bg-lime-500"></span> Available
                    </button>
                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm flex items-center gap-2 transition"
                            :class="selectedStatus === 'Reserved' ? 'bg-yellow-500/20 text-yellow-400' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                            @click="selectedStatus = 'Reserved'; statusOpen = false">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Reserved
                    </button>
                    <button type="button" class="w-full text-left px-4 py-2.5 text-sm flex items-center gap-2 transition"
                            :class="selectedStatus === 'Maintenance' ? 'bg-red-500/20 text-red-400' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                            @click="selectedStatus = 'Maintenance'; statusOpen = false">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Maintenance
                    </button>
                </div>
                @error('status') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black py-2.5 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.karts.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>