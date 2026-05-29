<x-app-layout>
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <a href="{{ route('public.news.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">&larr; Назад к новостям</a>
    
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $news->title }}</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $news->published_at->format('d.m.Y H:i') }}</p>

    @if($news->image_url)
        <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full rounded-lg shadow mb-6">
    @endif

    <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! nl2br(e($news->content)) !!}
    </div>
</div>
</x-app-layout>