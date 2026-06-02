@php use Carbon\Carbon; @endphp
<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('tracks.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-4 inline-block">
            ← Все трассы
        </a>
        @php
            $colors = [
                'Easy' => ['text' => 'text-lime-400', 'border' => 'border-lime-500', 'stroke_base' => '#052e16', 'stroke_glow' => '#a3e635', 'label' => 'EASY DIFFICULTY'],
                'Medium' => ['text' => 'text-yellow-400', 'border' => 'border-yellow-500', 'stroke_base' => '#422006', 'stroke_glow' => '#facc15', 'label' => 'MEDIUM DIFFICULTY'],
                'Hard' => ['text' => 'text-red-500', 'border' => 'border-red-500', 'stroke_base' => '#450a0a', 'stroke_glow' => '#ef4444', 'label' => 'HARD DIFFICULTY'],
            ];
            $c = $colors[$track->difficulty->name] ?? $colors['Easy'];
            $svgPath = $track->svg_code ?? "M 50 200 Q 150 50 250 150 T 450 200 T 250 350 T 50 200";
        @endphp
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight mb-2">
            Трасса <span class="{{ $c['text'] }}">{{ $track->name }}</span>
        </h1>
        <p class="text-gray-500 uppercase font-bold text-sm tracking-widest">{{ $c['label'] }}</p>
    </div>

    <div class="relative w-full h-[350px] md:h-[500px] mb-0 overflow-hidden rounded-t-xl border border-white/5 border-b-0 bg-black shadow-2xl">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-transparent to-transparent z-10"></div>
        
        <div class="absolute inset-0 flex items-center justify-center opacity-60 md:opacity-80 perspective-1000 pointer-events-none">
            <div class="transform-3d w-[900px] h-[900px]">
                <svg viewBox="0 0 500 400" class="w-full h-full">
                    <path d="{{ $svgPath }}" fill="none" stroke-linecap="square" stroke-linejoin="miter" stroke-width="30" stroke="{{ $c['stroke_base'] }}" />
                    <path d="{{ $svgPath }}" fill="none" stroke-linecap="square" stroke-linejoin="miter" stroke-width="30" stroke="{{ $c['stroke_glow'] }}" 
                          style="filter: drop-shadow(0 0 10px {{ $c['stroke_glow'] }}); stroke-dasharray: 100 800; animation: raceGlow 4s linear infinite;" />
                </svg>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 p-6 md:p-8 z-20 w-full">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="border-l-2 {{ $c['border'] }} pl-4">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Дистанция</p>
                    <p class="text-2xl md:text-3xl font-black text-white">{{ $track->length }}<span class="text-lg text-gray-500">м</span></p>
                </div>
                <div class="border-l-2 {{ $c['border'] }} pl-4">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Сложность</p>
                    <p class="text-2xl md:text-3xl font-black {{ $c['text'] }}">{{ $track->difficulty->name }}</p>
                </div>
                <div class="border-l-2 {{ $c['border'] }} pl-4">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Макс. уч.</p>
                    <p class="text-2xl md:text-3xl font-black text-white">{{ $track->max_participants }}</p>
                </div>
                <div class="border-l-2 {{ $c['border'] }} pl-4">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Цена / слот</p>
                    <p class="text-2xl md:text-3xl font-black text-white">{{ number_format($track->price_per_slot, 0, ',', ' ') }}<span class="text-lg text-gray-500">₽</span></p>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'today' }" class="bg-black/80 backdrop-blur-md border border-white/5 border-t-white/10 rounded-b-xl p-6 md:p-8 mb-8 shadow-2xl">
        
        <div class="flex border-b border-white/5 mb-6">
            <button @click="tab = 'today'" :class="tab === 'today' ? 'bg-white/5 {{ $c['text'] }} border-b-2 {{ $c['border'] }}' : 'text-gray-600 hover:text-white border-b-2 border-transparent'" class="flex-1 py-3 text-sm font-black uppercase tracking-wider transition">
                Сегодня
            </button>
            <button @click="tab = 'tomorrow'" :class="tab === 'tomorrow' ? 'bg-white/5 {{ $c['text'] }} border-b-2 {{ $c['border'] }}' : 'text-gray-600 hover:text-white border-b-2 border-transparent'" class="flex-1 py-3 text-sm font-black uppercase tracking-wider transition">
                Завтра
            </button>
        </div>

        <div x-show="tab === 'today'" x-transition>
            @php $todaySlots = $slots->get(today()->toDateString()) ?? collect(); @endphp
            @if($todaySlots->isEmpty())
                <div class="text-center text-gray-600 text-sm font-bold uppercase tracking-widest py-4">
                    На сегодня свободных слотов нет
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($todaySlots as $slot)
                        @php $isBusy = $slot->bookings->isNotEmpty(); @endphp
                        <div class="flex justify-between items-center p-3 bg-black/50 border-l-4 {{ $isBusy ? 'border-gray-700' : 'border-lime-500' }}">
                            <span class="font-mono text-white font-bold text-sm">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                            @if($isBusy)
                                <span class="bg-gray-800 text-gray-600 px-3 py-1 font-black text-xs uppercase cursor-not-allowed">Занят</span>
                            @else
                                @auth
                                    <a href="{{ route('bookings.create', ['slot_id' => $slot->id]) }}" class="bg-lime-500 hover:bg-lime-400 text-black px-3 py-1 font-black text-xs uppercase transition">Свободен</a>
                                @else
                                    <a href="{{ route('login') }}" class="border border-gray-700 text-gray-500 hover:text-lime-400 px-3 py-1 font-black text-xs uppercase transition">Войти</a>
                                @endauth
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div x-show="tab === 'tomorrow'" x-transition>
            @php $tomorrowSlots = $slots->get(Carbon::tomorrow()->toDateString()) ?? collect(); @endphp
            @if($tomorrowSlots->isEmpty())
                <div class="text-center text-gray-600 text-sm font-bold uppercase tracking-widest py-4">
                    На завтра свободных слотов нет
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($tomorrowSlots as $slot)
                        @php $isBusy = $slot->bookings->isNotEmpty(); @endphp
                        <div class="flex justify-between items-center p-3 bg-black/50 border-l-4 {{ $isBusy ? 'border-gray-700' : 'border-lime-500' }}">
                            <span class="font-mono text-white font-bold text-sm">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                            @if($isBusy)
                                <span class="bg-gray-800 text-gray-600 px-3 py-1 font-black text-xs uppercase cursor-not-allowed">Занят</span>
                            @else
                                @auth
                                    <a href="{{ route('bookings.create', ['slot_id' => $slot->id]) }}" class="bg-lime-500 hover:bg-lime-400 text-black px-3 py-1 font-black text-xs uppercase transition">Свободен</a>
                                @else
                                    <a href="{{ route('login') }}" class="border border-gray-700 text-gray-500 hover:text-lime-400 px-3 py-1 font-black text-xs uppercase transition">Войти</a>
                                @endauth
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-6 pt-4 border-t border-white/5 flex flex-col sm:flex-row gap-2">
            <a href="{{ route('schedule.index', ['track_id' => $track->id, 'date' => today()->toDateString()]) }}" class="flex-1 text-center bg-gray-800 hover:bg-lime-500 hover:text-black {{ $c['text'] }} px-3 py-2 font-black uppercase text-xs tracking-wider transition">
                Расписание на сегодня
            </a>
            <a href="{{ route('schedule.index', ['track_id' => $track->id]) }}" class="flex-1 text-center border border-gray-700 hover:border-white hover:text-white text-gray-500 px-3 py-2 font-black uppercase text-xs tracking-wider transition">
                Полное расписание
            </a>
        </div>
    </div>

    @if($track->description)
    <div class="glass-card rounded-xl p-6 border-l-4 {{ $c['border'] }}">
        <h3 class="text-lg font-black text-white uppercase tracking-wider mb-3">О трассе</h3>
        <p class="text-gray-400 text-sm font-semibold leading-relaxed">
            {{ $track->description }}
        </p>
    </div>
    @endif

</div>
</x-app-layout>