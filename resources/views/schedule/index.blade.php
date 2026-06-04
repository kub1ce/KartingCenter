@php use Carbon\Carbon; @endphp
<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <h1 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-red-500 pl-4">Расписание заездов</h1>

    <form method="GET" action="{{ route('schedule.index') }}" class="mb-10 space-y-6">
        
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Даты:</span>
                @if(request()->hasAny(['date', 'track_id', 'times']))
                    <a href="{{ route('schedule.index') }}" class="text-[10px] text-red-400 hover:text-red-300 uppercase tracking-widest font-bold transition">Сбросить всё</a>
                @endif
            </div>
            <div class="flex space-x-3 overflow-x-auto pb-4 scrollbar-hide">
                @foreach($dates as $d)
                    @php 
                        $dateVal = $d->toDateString(); 
                        $isChecked = in_array($dateVal, request('date', [])); 
                    @endphp
                    <label class="cursor-pointer flex-shrink-0">
                        <input type="checkbox" name="date[]" value="{{ $dateVal }}" class="hidden peer" {{ $isChecked ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="border {{ $isChecked ? 'bg-lime-500 text-black border-lime-500 font-black shadow-[0_0_15px_rgba(163,230,53,0.3)]' : 'border-gray-700 text-gray-500 hover:border-white hover:text-white' }} px-5 py-2 font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500 peer-checked:text-black peer-checked:border-lime-500 peer-checked:font-black peer-checked:shadow-[0_0_15px_rgba(163,230,53,0.3)]">
                            {{ $d->isoFormat('D MMM, dd') }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <span class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2 block">Трассы:</span>
            <div class="flex flex-wrap gap-3">
                @foreach($tracks as $track)
                    @php
                        $cTrack = [
                            'Easy' => ['border' => 'border-lime-500/50', 'text' => 'text-lime-400', 'hover' => 'hover:bg-lime-500 hover:text-black', 'active_bg' => 'bg-lime-500/20'],
                            'Medium' => ['border' => 'border-yellow-500/50', 'text' => 'text-yellow-400', 'hover' => 'hover:bg-yellow-500 hover:text-black', 'active_bg' => 'bg-yellow-500/20'],
                            'Hard' => ['border' => 'border-red-500/50', 'text' => 'text-red-400', 'hover' => 'hover:bg-red-500 hover:text-black', 'active_bg' => 'bg-red-500/20'],
                        ][$track->difficulty->name] ?? ['border' => 'border-gray-500/50', 'text' => 'text-gray-400', 'hover' => 'hover:bg-gray-500', 'active_bg' => 'bg-gray-500/20'];
                        
                        $isTrackChecked = in_array($track->id, request('track_id', []));
                    @endphp
                    <label class="cursor-pointer">
                        <input type="checkbox" name="track_id[]" value="{{ $track->id }}" class="hidden peer" {{ $isTrackChecked ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="border {{ $isTrackChecked ? $cTrack['border'] . ' ' . $cTrack['text'] . ' ' . $cTrack['active_bg'] : $cTrack['border'] . ' ' . $cTrack['text'] . ' ' . $cTrack['hover'] }} px-4 py-1.5 font-black uppercase text-xs tracking-widest transition">
                            {{ $track->name }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <span class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2 block">Время:</span>
            <div class="flex flex-wrap gap-2">
                @foreach($timeOptions as $time)
                    @php 
                        $timeVal = \Carbon\Carbon::parse($time->start_time)->format('H:i');
                        $isChecked = in_array($timeVal, request('times', [])); 
                    @endphp
                    <label class="cursor-pointer">
                        <input type="checkbox" name="times[]" value="{{ $timeVal }}" class="hidden peer" {{ $isChecked ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="border border-gray-700 text-gray-500 px-3 py-1.5 font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500/20 peer-checked:border-lime-500/50 peer-checked:text-lime-400 hover:border-white hover:text-white">
                            {{ \Carbon\Carbon::parse($time->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($time->end_time)->format('H:i') }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

    </form>

    @if($slotsData->isEmpty())
        <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
            Свободных слотов по выбранным фильтрам не найдено.
        </div>
    @else
        @foreach($slotsData as $date => $daySlots)
            <div class="mb-16">
                <h2 class="text-lg font-black text-white uppercase mb-8 tracking-wider flex items-center">
                    {{ Carbon::parse($date)->isoFormat('D MMMM, dddd') }}
                    @if(Carbon::parse($date)->isToday())
                        <span class="ml-3 text-xs font-medium text-lime-400 border border-lime-500/30 px-2 py-0.5 rounded-full">Сегодня</span>
                    @elseif(Carbon::parse($date)->isTomorrow())
                        <span class="ml-3 text-xs font-medium text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded-full">Завтра</span>
                    @endif
                </h2>

                @foreach($daySlots as $timeKey => $timeSlots)
                    <div class="md:flex mb-8">
                        
                        <div class="hidden md:flex flex-col items-center w-32 flex-shrink-0 border-r-2 border-white/10 relative pr-4">
                            <div class="absolute -right-[7px] top-1 w-3 h-3 bg-lime-500 rounded-full shadow-[0_0_10px_rgba(163,230,53,0.6)]"></div>
                            <div class="text-lg font-black text-white tracking-wider text-right w-full">{{ explode(' - ', $timeKey)[0] }}</div>
                            <div class="text-xs font-bold text-gray-500 tracking-wider text-right w-full">– {{ explode(' - ', $timeKey)[1] }}</div>
                        </div>

                        <div class="flex-1 w-full">
                            <div class="md:hidden mb-4">
                                <div class="text-base font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-3 py-1">
                                    {{ $timeKey }}
                                </div>
                            </div>

                            <div class="pl-0 md:pl-6 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                @php $busyStarted = false; @endphp
                                
                                @foreach($timeSlots as $item)
                                    @php 
                                        $isBusy = $item['is_busy'];
                                        $track = $item['track'];
                                        $cSlot = [
                                            'Easy' => ['stripe' => 'bg-lime-500', 'badge' => 'bg-lime-500/20 border-lime-500/50 text-lime-400', 'btn' => 'bg-lime-500 hover:bg-lime-400 text-black hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]'],
                                            'Medium' => ['stripe' => 'bg-yellow-500', 'badge' => 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400', 'btn' => 'bg-yellow-500 hover:bg-yellow-400 text-black hover:shadow-[0_0_15px_rgba(234,179,8,0.4)]'],
                                            'Hard' => ['stripe' => 'bg-red-500', 'badge' => 'bg-red-500/20 border-red-500/50 text-red-400', 'btn' => 'bg-red-500 hover:bg-red-400 text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]'],
                                        ][$track->difficulty->name] ?? ['stripe' => 'bg-gray-500', 'badge' => 'bg-gray-500/20 border-gray-500/50 text-gray-400', 'btn' => 'bg-gray-500 hover:bg-gray-400 text-white'];

                                        $separatorClass = '';
                                        if ($isBusy && !$busyStarted && !$loop->first) {
                                            $separatorClass = 'mt-4 md:mt-0 md:ml-6 md:border-l-2 md:border-red-500/20 md:pl-4';
                                            $busyStarted = true;
                                        }
                                    @endphp

                                    <div class="glass-card rounded-xl p-4 relative overflow-hidden group {{ $isBusy ? 'opacity-50' : '' }} {{ $separatorClass }}">
                                        <div class="track-stripe {{ $isBusy ? 'bg-gray-700 text-gray-700' : $cSlot['stripe'] }}" style="color: {{ $isBusy ? '#374151' : '' }}"></div>
                                        
                                        <div class="flex justify-between items-start mb-2 pl-4">
                                            <div class="text-sm font-black {{ $isBusy ? 'text-gray-600' : 'text-gray-400' }} uppercase tracking-wider">
                                                {{ $track->name }}
                                            </div>
                                            <div class="{{ $cSlot['badge'] }} text-[9px] font-black px-2 py-0.5 rounded tracking-widest">
                                                {{ $track->difficulty->name }}
                                            </div>
                                        </div>

                                        <div class="pl-4 mb-3 space-y-1">
                                            @if($isBusy)
                                                <div class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">
                                                    Нет свободных картов
                                                </div>
                                            @else
                                                @foreach($item['available_karts'] as $kart)
                                                    <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                        <span>{{ $kart['name'] }}</span>
                                                        <span class="text-lime-400">{{ $kart['count'] }} шт.</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-center pl-4 border-t border-white/5 pt-3">
                                            <div class="text-base font-black {{ $isBusy ? 'text-gray-600' : 'text-white' }}">
                                                {{ number_format($track->price_per_slot, 0, '.', ' ') }} ₽
                                            </div>
                                            
                                            @if(!$isBusy)
                                                @auth
                                                    @can('is-client')
                                                        <a href="{{ route('bookings.create', ['slot_id' => $item['id']]) }}" class="{{ $cSlot['btn'] }} px-3 py-1 font-black uppercase tracking-wider transition text-[10px]">
                                                            Забронировать
                                                        </a>
                                                    @endcan
                                                @else
                                                    <a href="{{ route('login') }}" class="border border-gray-700 text-gray-500 hover:text-lime-400 hover:border-lime-500/50 px-3 py-1 font-black uppercase tracking-wider transition text-[10px]">
                                                        Войти
                                                    </a>
                                                @endauth
                                            @else
                                                <span class="border border-gray-700 text-gray-600 px-3 py-1 font-black uppercase tracking-wider text-[10px] cursor-not-allowed">
                                                    Занято
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif

</div>
</x-app-layout>