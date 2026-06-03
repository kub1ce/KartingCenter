<x-admin-layout>
<div>
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4">Управление новостями</h1>
        <a href="{{ route('admin.news.create') }}" class="w-full sm:w-auto text-center bg-lime-500 hover:bg-lime-400 text-black px-5 py-2 font-black uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
            + Добавить новость
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-lime-900/20 border border-lime-500/30 p-4 text-lime-400 font-bold uppercase text-sm tracking-wider backdrop-blur-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="py-4 px-6 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Заголовок</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Статус</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Дата</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Действия</th>
                    </tr>
                </thead>
                <tbody class="text-gray-400 text-sm">
                    @foreach ($news as $item)
                    <tr class="border-b border-white/5 hover:bg-lime-500/5 transition-colors">
                        <td class="py-4 px-6 text-left font-bold text-white whitespace-nowrap">
                            {{ $item->title }}
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            @if($item->is_published)
                                <span class="bg-lime-500/20 border border-lime-500/50 text-lime-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Опубл.</span>
                            @else
                                <span class="bg-red-500/20 border border-red-500/50 text-red-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Черновик</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center font-mono text-xs text-gray-500 whitespace-nowrap">
                            {{ $item->published_at ? $item->published_at->format('d.m.Y H:i') : '—' }}
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500 hover:text-black font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                    Ред.
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Удалить новость?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                        Удал.
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 text-gray-500 text-xs font-bold uppercase tracking-widest">
        {{ $news->links() }}
    </div>
</div>
</x-admin-layout>