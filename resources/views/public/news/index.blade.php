<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <h1 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-lime-500 pl-4">Новости центра</h1>

    @if($news->isEmpty())
        <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
            Новостей пока нет
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
                <a href="{{ route('public.news.show', $item) }}" class="group block">
                    <div class="glass-card rounded-xl overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-[0_0_20px_rgba(163,230,53,0.1)] hover:-translate-y-1">
                        
                        @if($item->image_url)
                            <div class="h-48 bg-black/50 overflow-hidden border-b border-white/5 relative">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-70 group-hover:opacity-100">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            </div>
                        @else
                            <div class="h-48 bg-black/50 overflow-hidden border-b border-white/5 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col">
                            <p class="text-xs text-lime-400 font-bold uppercase tracking-widest mb-2 font-mono">
                                {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d.m.Y') : $item->created_at->format('d.m.Y') }}
                            </p>
                            
                            <h3 class="text-lg font-black text-white uppercase mb-3 group-hover:text-lime-400 transition-colors tracking-wide">
                                {{ $item->title }}
                            </h3>
                            
                            <p class="text-gray-500 text-sm flex-1 mb-4 leading-relaxed">
                                {{ Str::limit(strip_tags($item->content), 100) }}
                            </p>
                            
                            <div class="mt-auto text-xs font-bold text-gray-600 uppercase tracking-widest group-hover:text-lime-400 transition-colors flex items-center">
                                Читать далее 
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
</x-app-layout>