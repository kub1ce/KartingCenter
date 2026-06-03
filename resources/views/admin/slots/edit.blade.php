<x-admin-layout>
<div>
    <a href="{{ route('admin.slots.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-6 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Все слоты
    </a>

    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-yellow-500 pl-4 mb-8">Панель слота #{{ $slot->id }}</h1>

    <div class="flex flex-col md:flex-row gap-8">
        
        <div class="w-full md:w-1/2">
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Параметры</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-xs font-bold uppercase">Дата</span>
                        <span class="text-white font-mono font-bold">{{ $slot->date->isoFormat('D MMMM YYYY, dddd') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-xs font-bold uppercase">Время</span>
                        <span class="text-white font-mono font-bold">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-xs font-bold uppercase">Трасса</span>
                        <span class="text-white font-bold">{{ $slot->track->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-xs font-bold uppercase">Статус</span>
                        @if($slot->is_blocked)
                            <span class="bg-red-500/20 border border-red-500/50 text-red-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Заблокирован</span>
                        @else
                            <span class="bg-lime-500/20 border border-lime-500/50 text-lime-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Активен</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 space-y-6">
            
            <div class="glass-card rounded-xl p-6 {{ $slot->is_blocked ? 'border-l-4 border-red-500/50' : 'border-l-4 border-lime-500/50' }}">
                <h2 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Управление доступностью</h2>
                <form action="{{ route('admin.slots.update', $slot->id) }}" method="POST">
                    @csrf @method('PUT')
                    <p class="text-gray-400 text-xs mb-4 leading-relaxed">
                        Блокировка закрывает слот для бронирования клиентами. Используйте для техобслуживания или закрытия трассы.
                    </p>
                    <input type="hidden" name="is_blocked" value="{{ $slot->is_blocked ? '0' : '1' }}">
                    <button type="submit" class="w-full {{ $slot->is_blocked ? 'bg-lime-500 hover:bg-lime-400 text-black' : 'bg-red-500 hover:bg-red-400 text-white' }} font-black py-3 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
                        {{ $slot->is_blocked ? 'Разблокировать слот' : 'Заблокировать слот' }}
                    </button>
                </form>
            </div>

            @if(!$slot->is_blocked && $slot->date >= today())
            <div class="glass-card rounded-xl p-6 border-l-4 border-yellow-500/50">
                <h2 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Ручное бронирование</h2>
                <p class="text-gray-400 text-xs mb-4 leading-relaxed">
                    Создайте бронь вручную, если клиент звонит по телефону.
                </p>
                <a href="{{ route('admin.bookings.create', ['slot_id' => $slot->id]) }}" class="w-full block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-black py-3 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                    Создать бронь на этот слот
                </a>
            </div>
            @endif

        </div>
    </div>
</div>
</x-admin-layout>