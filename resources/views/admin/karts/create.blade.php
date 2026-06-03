<x-admin-layout>
<div>
    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4 mb-8">Добавить карт</h1>

    <div class="glass-card p-6 rounded-xl max-w-2xl" x-data="{ 
        typeOpen: false, selectedTypeId: '{{ old('kart_type_id', '') }}',
        statusOpen: false, selectedStatus: '{{ old('status', 'Available') }}'
    }">
        <form action="{{ route('admin.karts.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="number">Номер карта</label>
                <input type="text" name="number" id="number" value="{{ old('number') }}" 
                       class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono font-bold" placeholder="Например: 05">
                @error('number') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5 relative">
                <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Тип карта</label>
                <input type="hidden" name="kart_type_id" x-model="selectedTypeId">
                
                <button type="button" @click="typeOpen = !typeOpen" 
                        class="w-full bg-black/50 border border-white/10 text-white py-3 pl-4 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm text-left flex justify-between items-center">
                    <span class="flex items-center gap-2">
                        <span x-show="!selectedTypeId" class="text-gray-500">Выберите тип</span>
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
                    Добавить
                </button>
                <a href="{{ route('admin.karts.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>