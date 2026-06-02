<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <h1 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-red-500 pl-4">Наши трассы</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($tracks as $track)
            @php
                $colors = [
                    'Easy' => ['text' => 'text-lime-400', 'bg' => 'bg-lime-500/20', 'border' => 'border-lime-500/50', 'label' => 'EASY', 'labelColor' => 'text-lime-400', 'glow' => '0 10px 30px -10px rgba(163, 230, 53, 0.4)'],
                    'Medium' => ['text' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50', 'label' => 'MEDIUM', 'labelColor' => 'text-yellow-400', 'glow' => '0 10px 30px -10px rgba(234, 179, 8, 0.4)'],
                    'Hard' => ['text' => 'text-red-500', 'bg' => 'bg-red-500/20', 'border' => 'border-red-500/50', 'label' => 'HARD', 'labelColor' => 'text-red-400', 'glow' => '0 10px 30px -10px rgba(239, 68, 68, 0.4)'],
                ];
                $c = $colors[$track->difficulty->name] ?? $colors['Easy'];
                $svgPath = $track->svg_code ?? "M 50 200 Q 150 50 250 150 T 450 200 T 250 350 T 50 200";
            @endphp

            <div class="glass-card rounded-xl overflow-hidden group cursor-pointer transition-all duration-300 hover:shadow-[var(--card-glow)]" style="--card-glow: {{ $c['glow'] }}">
                <div class="h-56 bg-black/50 relative flex items-center justify-center p-4 border-b border-white/5">
                    <svg viewBox="0 0 500 400" class="w-full h-full">
                        <path class="track-base" d="{{ $svgPath }}" />
                        <path class="track-glow {{ $c['text'] }}" d="{{ $svgPath }}" />
                        <path class="track-dashes {{ $c['text'] }}" d="{{ $svgPath }}" />
                    </svg>
                    <div class="absolute top-4 right-4 {{ $c['bg'] }} border {{ $c['border'] }} {{ $c['labelColor'] }} text-xs font-black px-3 py-1 rounded-full tracking-widest backdrop-blur-sm">
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
                    
                    <a href="{{ route('tracks.show', $track) }}" class="block mt-5 bg-gray-800 hover:bg-lime-500 hover:text-black {{ $c['text'] }} px-4 py-3 font-black uppercase text-xs tracking-wider text-center transition">
                        Подробнее
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
</x-app-layout>