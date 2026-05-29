<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Новости картинг-центра</h1>

    @if($news->isEmpty())
        <p class="text-gray-500">Пока новостей нет.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
                <a href="{{ route('public.news.show', $item->id) }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">Нет фото</div>
                    @endif
                    <div class="p-4">
                        <h2 class="font-bold text-lg text-gray-800 mb-2">{{ Str::limit($item->title, 50) }}</h2>
                        <p class="text-sm text-gray-500">{{ $item->published_at->format('d.m.Y') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $news->links() }}</div>
    @endif
</div>
</x-app-layout>