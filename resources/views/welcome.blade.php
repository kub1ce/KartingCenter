<x-app-layout>
<div class="cyber-grid min-h-screen px-16 sm:px-32 lg:px-64">

    <div class="mb-20 text-center relative py-16">
        <div class="absolute inset-0"></div>
        <div class="relative z-10">
            <h1 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tight mb-4">
                Картинг <br><span class="text-red-600" style="text-shadow: 0 0 25px rgba(220, 38, 38, 0.6);">без тормозов</span>
            </h1>
            <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto mb-10 uppercase tracking-widest font-semibold">
                Выбери трассу. Забронируй время. Покажи кто на треке хозяин.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
                <a href="{{ route('schedule.index', ['date' => now()->format('Y-m-d')]) }}" class="bg-lime-500 hover:bg-lime-400 text-black px-8 py-3 font-black uppercase tracking-wider transition text-sm hover:shadow-[0_0_25px_rgba(163,230,53,0.4)]">
                    Забронировать сегодня
                </a>
                <a href="{{ route('schedule.index') }}" class="border-2 border-gray-700 text-gray-400 hover:border-white hover:text-white px-8 py-3 font-black uppercase tracking-wider transition text-sm">
                    Полное расписание
                </a>
            </div>
        </div>
    </div>

    @if($promotions->isNotEmpty())
    <div class="mb-20">
        <a class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-lime-500 pl-4" href="{{ route('public.promotions.index') }}">Горячие акции</a>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($promotions as $promo)
                <div class="glass-card rounded-xl p-6" style="--glow-color: rgba(239, 68, 68, 0.3);">
                    <span class="bg-red-600 text-white text-sm font-black px-3 py-1 inline-block mb-4">-{{ $promo->discount_percent }}%</span>
                    <h3 class="text-white font-bold text-lg mb-2">{{ $promo->title }}</h3>
                    <p class="text-gray-500 text-sm">{{ Str::limit($promo->description, 100) }}</p>
                    <p class="text-xs text-gray-600 mt-3 uppercase font-bold">До {{ \Carbon\Carbon::parse($promo->end_date)->format('d.m.Y') }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- MAP & LOCATION -->
    <div class="mb-20">
        <h2 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-lime-500 pl-4">Как нас найти</h2>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <div id="mapWrapper" class="lg:col-span-3 relative rounded-xl overflow-hidden border border-white/10 h-80 lg:h-auto" style="box-shadow: 0 0 30px rgba(163, 230, 53, 0.1);">
                <iframe 
                    src="https://yandex.ru/map-widget/v1/?um=constructor%3A52cf18c912d2c721f7afcb59696b5f4267fbe90566e266d11bb7af0987cae253&amp;source=constructor" 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    style="filter: invert(90%) hue-rotate(180deg) contrast(1.2) brightness(0.8);"
                    allowfullscreen>
                </iframe>
                
                <div id="mapOverlay" class="absolute inset-0 bg-transparent z-10 cursor-pointer"></div>
            </div>

            <div class="lg:col-span-2 glass-card rounded-xl p-8 flex flex-col justify-center" style="--glow-color: rgba(163, 230, 53, 0.15);">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Адрес</h3>
                        <p class="text-white font-bold text-lg">г. Екатеринбург, ул. Мира, 32</p>
                    </div>
                    
                    <div class="h-px w-full bg-gradient-to-r from-lime-500/50 to-transparent"></div>

                    <div>
                        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Бронирование</h3>
                        <p class="text-lime-400 font-bold text-lg hover:text-lime-300 transition cursor-pointer">+7 (343) 123-45-67</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Почта</h3>
                        <p class="text-white font-bold text-lg hover:text-lime-400 transition cursor-pointer">admin@karting.local</p>
                    </div>

                    <div class="pt-4">
                        <a href="https://yandex.ru/maps/-/CPXjr-Pz" target="_blank" class="inline-block bg-gray-800 hover:bg-lime-500 hover:text-black text-lime-400 px-5 py-2.5 font-black uppercase text-xs tracking-wider transition">
                            Построить маршрут
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TRACKS GRID -->
    <a class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-lime-500 pl-4" href="{{ route('tracks.index') }}">Наши трассы</a>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-20">
        
        @foreach($tracks as $track)
            @php
                $colors = [
                    'Easy' => ['text' => 'text-lime-400', 'bg' => 'bg-lime-500/20', 'border' => 'border-lime-500/50', 'label' => 'EASY', 'labelColor' => 'text-lime-400'],
                    'Medium' => ['text' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50', 'label' => 'MEDIUM', 'labelColor' => 'text-yellow-400'],
                    'Hard' => ['text' => 'text-red-500', 'bg' => 'bg-red-500/20', 'border' => 'border-red-500/50', 'label' => 'HARD', 'labelColor' => 'text-red-400'],
                ];
                $c = $colors[$track->difficulty->name] ?? $colors['Easy'];
                
                $defaultPaths = [
                    'Easy' => 'M 80 200 C 80 80, 420 80, 420 200 C 420 320, 80 320, 80 200',
                    'Medium' => 'M 100 50 L 400 50 L 450 150 L 350 200 L 400 300 L 150 350 L 50 250 Z',
                    'Hard' => 'M 80 50 L 420 50 L 450 120 L 300 180 L 420 260 L 380 350 L 100 350 L 60 260 L 200 180 L 60 120 Z'
                ];
                $svgPath = $track->svg_code ?? ($defaultPaths[$track->difficulty->name] ?? $defaultPaths['Easy']);
            @endphp

            <div class="glass-card rounded-xl overflow-hidden group cursor-pointer" style="--glow-color: rgba(163, 230, 53, 0.2);">
                <div class="h-56 bg-black/50 relative flex items-center justify-center p-4 border-b border-white/5 overflow-hidden">
                    
                    <svg viewBox="0 0 500 400" class="w-[120%] h-full opacity-80 group-hover:opacity-100 transition-opacity duration-500" style="overflow: visible;">
                        <path class="track-base" d="{{ $svgPath }}" />
                        <path class="track-glow {{ $c['text'] }}" d="{{ $svgPath }}" />
                        <path class="track-dashes {{ $c['text'] }}" d="{{ $svgPath }}" />
                    </svg>

                    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-[rgba(10,10,10,0.95)] to-transparent pointer-events-none z-10"></div>
                    
                    <div class="absolute top-4 right-4 {{ $c['bg'] }} border {{ $c['border'] }} {{ $c['labelColor'] }} text-xs font-black px-3 py-1 rounded-full tracking-widest backdrop-blur-sm z-20">
                        {{ $c['label'] }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-black text-white uppercase mb-3">{{ $track->name }}</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs font-bold uppercase tracking-wider text-gray-500 border-t border-white/5 pt-3">
                        <span>Длина:</span> <span class="{{ $c['text'] }} text-right">{{ $track->length }}м</span>
                        <span>Цена/слот:</span> <span class="{{ $c['text'] }} text-right">{{ number_format($track->price_per_slot, 0, ',', ' ') }}₽</span>
                        <span>Макс. уч.:</span> <span class="{{ $c['text'] }} text-right">{{ $track->max_participants }}</span>
                    </div>
                    <a href="{{ route('tracks.show', $track) }}" class="inline-block mt-5 bg-gray-800 hover:bg-lime-500 hover:text-black {{ $c['text'] }} px-4 py-2 font-black uppercase text-xs tracking-wider transition">
                        Подробнее
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapWrapper = document.getElementById('mapWrapper');
    const mapOverlay = document.getElementById('mapOverlay');

    if (mapWrapper && mapOverlay) {
        mapOverlay.addEventListener('wheel', function (e) {
            e.preventDefault();
            window.scrollBy({
                top: e.deltaY,
                behavior: 'auto'
            });
        }, { passive: false });

        mapOverlay.addEventListener('mousedown', function () {
            mapOverlay.style.pointerEvents = 'none';
        });

        mapWrapper.addEventListener('mouseleave', function () {
            mapOverlay.style.pointerEvents = 'auto';
        });
    }
});
</script>
</x-app-layout>