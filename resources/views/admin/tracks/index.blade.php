<x-admin-layout>
<div>
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4">Управление трассами</h1>
        <a href="{{ route('admin.tracks.create') }}" class="w-full sm:w-auto text-center bg-lime-500 hover:bg-lime-400 text-black px-5 py-2 font-black uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
            + Добавить трассу
        </a>
    </div>

    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="py-4 px-6 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Трасса</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Длина</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Сложность</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Цена / слот</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Действия</th>
                    </tr>
                </thead>
                <tbody class="text-gray-400 text-sm">
                    @foreach ($tracks as $track)
                    @php
                        $colors = [
                            'Easy' => ['text' => 'text-lime-400', 'bg' => 'bg-lime-500/20', 'border' => 'border-lime-500/50'],
                            'Medium' => ['text' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50'],
                            'Hard' => ['text' => 'text-red-400', 'bg' => 'bg-red-500/20', 'border' => 'border-red-500/50'],
                        ];
                        $c = $colors[$track->difficulty->name] ?? $colors['Easy'];
                        $svgPath = $track->svg_code ?? "M 50 200 Q 150 50 250 150 T 450 200 T 250 350 T 50 200";
                    @endphp
                    <tr class="border-b border-white/5 hover:bg-lime-500/5 transition-colors">
                        <td class="py-4 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-12 flex-shrink-0">
                                    <svg viewBox="0 0 500 400" class="w-full h-full" style="overflow: visible;">
                                        <path class="track-glow {{ $c['text'] }}" d="{{ $svgPath }}" />
                                        <path class="track-dashes {{ $c['text'] }}" d="{{ $svgPath }}" />
                                    </svg>
                                </div>
                                <span class="font-bold text-white">{{ $track->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center font-mono text-xs text-gray-300 whitespace-nowrap">{{ $track->length }} м</td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="{{ $c['bg'] }} border {{ $c['border'] }} {{ $c['text'] }} py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $track->difficulty->name }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-white whitespace-nowrap">{{ number_format($track->price_per_slot, 0, ',', ' ') }} ₽</td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <a href="{{ route('admin.tracks.edit', $track->id) }}" class="border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500 hover:text-black font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                Ред.
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-admin-layout>