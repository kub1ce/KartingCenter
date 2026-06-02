<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-4xl mx-auto">

    <a href="{{ route('public.news.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-8 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Все новости
    </a>

    <article>
        <div class="mb-8 border-l-4 border-lime-500 pl-4">
            <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-2">{{ $news->title }}</h1>
            <p class="text-lime-400 font-bold uppercase tracking-widest text-sm font-mono">
                {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d.m.Y H:i') : $news->created_at->format('d.m.Y H:i') }}
            </p>
        </div>

        @if($news->image_url)
            <div class="mb-8 rounded-xl overflow-hidden border border-white/5 shadow-2xl">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full object-cover max-h-[500px]">
            </div>
        @endif

        <div class="glass-card rounded-xl p-6 md:p-8 text-gray-300 leading-relaxed space-y-4">
            {!! nl2br(e($news->content)) !!}
        </div>
    </article>

</div>
</x-app-layout>