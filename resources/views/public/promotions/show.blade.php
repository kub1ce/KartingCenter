<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-4xl mx-auto">

    <a href="{{ route('public.promotions.index') }}" class="text-gray-500 hover:text-red-400 uppercase text-xs font-bold tracking-widest transition mb-8 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Все акции
    </a>

    <article>
        <div class="mb-8 flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1 border-l-4 border-red-500 pl-4">
                <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-2">{{ $promotion->title }}</h1>
                <div class="flex items-center space-x-2 mt-2">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">
                        Действует до <span class="text-yellow-400">{{ \Carbon\Carbon::parse($promotion->end_date)->format('d.m.Y') }}</span>
                    </span>
                </div>
            </div>
            
            <div class="bg-red-500/20 border-2 border-red-500/50 text-red-500 text-2xl md:text-4xl font-black px-6 py-3 rounded-sm shadow-[0_0_20px_rgba(239,68,68,0.4)] flex-shrink-0 text-center">
                -{{ $promotion->discount_percent }}%
            </div>
        </div>

        <div class="glass-card rounded-xl p-6 md:p-8 border-l-4 border-red-500/50 text-gray-300 leading-relaxed space-y-4 bg-black/50">
            {!! nl2br(e($promotion->description)) !!}
        </div>

        <div class="mt-10 text-center">
            <p class="text-gray-600 text-xs font-bold uppercase tracking-widest mb-4">Успейте воспользоваться скидкой!</p>
            <a href="{{ route('schedule.index') }}" class="bg-lime-500 hover:bg-lime-400 text-black px-8 py-3 font-black uppercase tracking-wider transition text-sm hover:shadow-[0_0_25px_rgba(163,230,53,0.4)] inline-block">
                Забронировать заезд
            </a>
        </div>

    </article>

</div>
</x-app-layout>