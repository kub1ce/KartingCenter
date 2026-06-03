<x-admin-layout>
<div>
    <h1 class="text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4 mb-8">Создание новости</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-1/2">
            <div class="glass-card p-6 rounded-xl">
                <form action="{{ route('admin.news.store') }}" method="POST" id="newsForm">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="title">Заголовок</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-bold" oninput="updatePreview()">
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="content">Текст новости</label>
                        <textarea name="content" id="content" rows="6" 
                                  class="w-full bg-black/50 border border-white/10 text-gray-300 py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm leading-relaxed" oninput="updatePreview()">{{ old('content') }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="image_url">Ссылка на изображение (URL)</label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" 
                               class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono" oninput="updatePreview()">
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_published" value="1" id="is_published" class="w-5 h-5 rounded bg-black border-gray-700 text-lime-500 focus:ring-lime-500 focus:ring-offset-black cursor-pointer">
                        <label for="is_published" class="ml-3 text-gray-400 text-sm font-bold uppercase tracking-wider cursor-pointer">Опубликовать сразу</label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black py-2.5 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/2">
            <h2 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4">Предпросмотр</h2>
            
            <div class="glass-card rounded-xl overflow-hidden group">
                <div class="h-48 bg-black/50 overflow-hidden border-b border-white/5 relative">
                    <div id="preview-image-container" class="w-full h-full flex items-center justify-center text-gray-600 bg-gray-900/50">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <img id="preview-image" src="" alt="" class="w-full h-full object-cover hidden absolute inset-0">
                </div>
                
                <div class="p-6">
                    <p class="text-xs text-lime-400 font-bold uppercase tracking-widest mb-2 font-mono">Сегодня</p>
                    <h2 id="preview-title" class="text-lg font-black text-white uppercase mb-3 tracking-wide">Заголовок новости</h2>
                    <p id="preview-content" class="text-gray-500 text-sm leading-relaxed">Текст вашей новости появится здесь...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
                placeholder.innerHTML = '<span class="text-red-500 text-xs font-bold uppercase">Ошибка загрузки</span>';
            };
        } else {
            img.classList.add('hidden');
            placeholder.classList.remove('hidden');
            placeholder.innerHTML = '<svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
        }
    }
</script>
</x-admin-layout>