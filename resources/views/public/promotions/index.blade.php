<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <h1 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-red-500 pl-4">Акции и скидки</h1>

    @if($promotions->isEmpty())
        <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
            Действующих акций сейчас нет
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promotions as $promo)
                <a href="{{ route('public.promotions.show', $promo) }}" class="group block">
                    <div class="glass-card rounded-xl overflow-hidden h-full flex flex-col border-l-4 border-red-500/50 group-hover:border-red-500 transition-all duration-300 group-hover:shadow-[0_0_20px_rgba(239,68,68,0.15)] group-hover:-translate-y-1">
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="inline-block bg-red-500/20 border border-red-500/50 text-red-500 text-sm font-black px-3 py-1 rounded-sm mb-4 shadow-[0_0_10px_rgba(239,68,68,0.3)] self-start">
                                -{{ $promo->discount_percent }}%
                            </div>
                            
                            <h3 class="text-xl font-black text-white uppercase mb-3 tracking-wide">{{ $promo->title }}</h3>
                            <p class="text-gray-400 text-sm mb-5 leading-relaxed flex-1">{{ Str::limit($promo->description, 120) }}</p>
                            
                            <div class="border-t border-white/5 pt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">
                                        До <span class="text-yellow-400">{{ \Carbon\Carbon::parse($promo->end_date)->format('d.m.Y') }}</span>
                                    </span>
                                </div>
                                
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest group-hover:text-red-400 transition-colors flex items-center">
                                    Подробнее 
                                    <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
</x-app-layout>