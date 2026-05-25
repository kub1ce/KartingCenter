<x-admin-layout>
<div class="flex gap-8">
    
    <div class="w-1/2">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактирование новости</h1>

        <div class="bg-white p-6 rounded shadow-md">
            <form action="{{ route('admin.news.update', $news->id) }}" method="POST" id="newsForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Заголовок</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" oninput="updatePreview()">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content">Текст новости</label>
                    <textarea name="content" id="content" rows="6" 
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" oninput="updatePreview()">{{ old('content', $news->content) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="image_url">Ссылка на изображение (URL)</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $news->image_url) }}" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" oninput="updatePreview()">
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" class="form-checkbox h-5 w-5 text-blue-600" @checked(old('is_published', $news->is_published))>
                        <span class="ml-2 text-gray-700 text-sm font-bold">Опубликована</span>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="text-gray-600 hover:text-gray-800">Отмена</a>
                </div>
            </form>
        </div>
    </div>

    <div class="w-1/2">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Предпросмотр</h1>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div id="preview-image-container" class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 @if($news->image_url) hidden @endif">
                <span>Нет изображения</span>
            </div>
            <img id="preview-image" src="{{ $news->image_url }}" alt="" class="w-full h-48 object-cover @if(!$news->image_url) hidden @endif">
            
            <div class="p-6">
                <h2 id="preview-title" class="text-xl font-bold text-gray-800 mb-2">{{ $news->title }}</h2>
                <p id="preview-content" class="text-gray-600 text-sm">{{ $news->content }}</p>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });

    function updatePreview() {
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;
        const imageUrl = document.getElementById('image_url').value;

        document.getElementById('preview-title').innerText = title || 'Заголовок новости';
        document.getElementById('preview-content').innerText = content || 'Текст вашей новости появится здесь...';

        const img = document.getElementById('preview-image');
        const placeholder = document.getElementById('preview-image-container');

        if (imageUrl) {
            img.src = imageUrl;
            img.classList.remove('hidden');
            placeholder.classList.add('hidden');
            
            img.onerror = function() {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
                placeholder.innerHTML = '<span>Ошибка загрузки картинки</span>';
            };
        } else {
            img.classList.add('hidden');
            placeholder.classList.remove('hidden');
            placeholder.innerHTML = '<span>Нет изображения</span>';
        }
    }
</script>
</x-admin-layout>