<x-admin-layout>
<div>
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4">Управление расписанием</h1>
        <a href="{{ route('admin.slots.generate') }}" class="w-full sm:w-auto text-center bg-lime-500 hover:bg-lime-400 text-black px-5 py-2 font-black uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
            + Сгенерировать расписание
        </a>
    </div>

    @if(!$isSearchMode)
    <div class="glass-card rounded-xl p-3 mb-6 flex items-center justify-between">
        <a href="{{ route('admin.slots.index', array_merge(request()->except('week'), ['week' => $weekOffset - 1])) }}" class="flex items-center gap-2 border border-gray-700 text-gray-500 hover:border-white hover:text-white px-4 py-2 font-black uppercase text-xs tracking-widest transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Пред. неделя
        </a>
        
        <div class="text-center">
            <div class="text-white font-black uppercase tracking-widest text-sm">
                {{ $startOfWeek->isoFormat('D MMM') }} — {{ $endOfWeek->isoFormat('D MMM Y') }}
            </div>
            @if($weekOffset == 0)
                <span class="text-[10px] font-black text-lime-400 uppercase tracking-widest">Текущая неделя</span>
            @endif
        </div>

        <a href="{{ route('admin.slots.index', array_merge(request()->except('week'), ['week' => $weekOffset + 1])) }}" class="flex items-center gap-2 border border-gray-700 text-gray-500 hover:border-white hover:text-white px-4 py-2 font-black uppercase text-xs tracking-widest transition">
            След. неделя
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
    @endif

    <div class="glass-card p-5 rounded-xl mb-8">
        <form action="{{ route('admin.slots.index') }}" method="GET" class="space-y-5">
            
            @if(!$isSearchMode)
                <input type="hidden" name="week" value="{{ $weekOffset }}">
            @endif

            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Трассы</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tracks as $track)
                        <label class="cursor-pointer group">
                            <input type="checkbox" name="track_id[]" value="{{ $track->id }}" class="hidden peer" {{ in_array($track->id, (array) request('track_id', [])) ? 'checked' : '' }}>
                            <div class="border border-white/10 text-gray-500 px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500/20 peer-checked:border-lime-500/50 peer-checked:text-lime-400 hover:border-white/30 hover:text-white rounded-md">
                                {{ $track->name }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-white/5"></div>

            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                
                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Статус</label>
                    <select name="status" class="appearance-none bg-black/50 border border-white/10 text-white h-10 pl-3 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs"
                            style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Все активные</option>
                        <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Свободные</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Забронированные</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Заблокированные</option>
                        <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Архив</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Доп. фильтры</label>
                    <label class="h-10 flex items-center gap-2 cursor-pointer group border border-white/10 hover:border-white/30 px-3 rounded-md transition">
                        <input type="checkbox" name="show_past" value="1" 
                               class="w-4 h-4 rounded bg-black border-gray-700 text-lime-500 focus:ring-lime-500 focus:ring-offset-black"
                               {{ request('show_past') ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-gray-400 group-hover:text-white transition uppercase">Прошедшие дни</span>
                    </label>
                </div>

                <div class="flex items-end gap-3">
                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">С</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="bg-black/50 border border-white/10 text-white h-10 px-3 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs font-mono" style="color-scheme: dark;">
                    </div>
                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">По</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="bg-black/50 border border-white/10 text-white h-10 px-3 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs font-mono" style="color-scheme: dark;">
                    </div>
                </div>

                <div class="flex gap-3 items-center h-10 ml-auto">
                    <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black h-10 px-5 uppercase text-[10px] tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.3)]">Применить</button>
                    <a href="{{ route('admin.slots.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition inline-flex items-center h-full">Сбросить</a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-lime-900/20 border border-lime-500/30 p-4 text-lime-400 font-bold uppercase text-sm tracking-wider backdrop-blur-sm">{{ session('success') }}</div>
    @endif

    @if($groupedSlots->isEmpty())
        <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
            Слотов по заданным критериям не найдено.
        </div>
    @else
        @foreach ($groupedSlots as $date => $daySlots)
        @php $isPast = \Carbon\Carbon::parse($date)->lt(today()); @endphp
        <div class="mb-8 {{ $isPast ? 'opacity-40' : '' }}">
            <h2 class="text-lg font-black text-white uppercase mb-4 tracking-wider flex items-center border-l-4 {{ $isPast ? 'border-gray-600 pl-3' : 'border-lime-500 pl-3' }}">
                {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM, dddd') }}
                @if(\Carbon\Carbon::parse($date)->isToday())
                    <span class="ml-3 text-[10px] font-black text-lime-400 border border-lime-500/30 px-2 py-0.5 rounded-full">СЕГОДНЯ</span>
                @endif
            </h2>

            <div class="glass-card rounded-xl overflow-x-auto">
                <table class="min-w-full w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10">
                            <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Время</th>
                            <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Трасса</th>
                            <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Бронь / Статус</th>
                            <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-400 text-sm">
                        @foreach ($daySlots as $slot)
                        @php $hasBookings = $slot->bookings->isNotEmpty(); @endphp
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-3 px-5 font-mono font-bold text-white text-xs">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                            </td>
                            <td class="py-3 px-5 font-bold text-xs uppercase">
                                {{ $slot->track->name ?? '—' }}
                            </td>
                            <td class="py-3 px-5 text-center">
                                @if($isPast)
                                    <span class="bg-gray-500/20 border border-gray-500/50 text-gray-500 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Прошедший</span>
                                @elseif($slot->is_blocked)
                                    <span class="bg-red-500/20 border border-red-500/50 text-red-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Заблокирован</span>
                                @elseif($hasBookings)
                                    <span class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Есть бронь</span>
                                @else
                                    <span class="bg-lime-500/20 border border-lime-500/50 text-lime-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Свободен</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(!$isPast)
                                        <form action="{{ route('admin.slots.update', $slot->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="is_blocked" value="{{ $slot->is_blocked ? '0' : '1' }}">
                                            <button type="submit" class="border {{ $slot->is_blocked ? 'border-lime-500/50 text-lime-400 hover:bg-lime-500 hover:text-black' : 'border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white' }} font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                                {{ $slot->is_blocked ? 'Открыть' : 'Блок' }}
                                            </button>
                                        </form>

                                        @if(!$slot->is_blocked)
                                            <a href="{{ route('admin.bookings.create', ['slot_id' => $slot->id]) }}" class="border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500 hover:text-black font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                                Бронь
                                            </a>
                                        @endif
                                    @endif

                                    <a href="{{ route('admin.slots.edit', $slot->id) }}" class="text-gray-600 hover:text-white transition text-xs font-bold uppercase tracking-widest px-1">Детали</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        @if($isSearchMode && $paginatedSlots)
            <div class="mt-8 flex justify-center">
                {{ $paginatedSlots->links() }}
            </div>
        @endif
    @endif
</div>
</x-admin-layout>