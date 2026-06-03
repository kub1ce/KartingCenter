<x-admin-layout>
<div>
    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-red-500 pl-4 mb-8">Новая акция</h1>

    <div class="flex flex-col lg:flex-row gap-8" x-data="{ title: '', description: '', discount: 0, startDate: '', endDate: '' }">
        
        <div class="w-full lg:w-1/2">
            <div class="glass-card p-6 rounded-xl">
                <form action="{{ route('admin.promotions.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="title">Название акции</label>
                        <input type="text" name="title" id="title" x-model="title" value="{{ old('title') }}" 
                               class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-red-500 focus:ring-0 focus:outline-none transition text-sm font-bold">
                        @error('title') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="description">Описание</label>
                        <textarea name="description" id="description" rows="4" x-model="description"
                                  class="w-full bg-black/50 border border-white/10 text-gray-300 py-3 px-4 rounded-md focus:border-red-500 focus:ring-0 focus:outline-none transition text-sm leading-relaxed">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="discount_percent">Процент скидки</label>
                        <div class="relative">
                            <input type="number" name="discount_percent" id="discount_percent" min="1" max="99" x-model="discount" value="{{ old('discount_percent') }}" 
                                   class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 pr-10 rounded-md focus:border-red-500 focus:ring-0 focus:outline-none transition text-sm font-bold font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-red-400 font-bold">%</span>
                        </div>
                        @error('discount_percent') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-4 mb-5">
                        <div class="w-1/2">
                            <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="start_date">Начало</label>
                            <input type="date" name="start_date" id="start_date" x-model="startDate" value="{{ old('start_date') }}" 
                                   class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-red-500 focus:ring-0 focus:outline-none transition text-sm font-mono" style="color-scheme: dark;">
                            @error('start_date') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest" for="end_date">Окончание</label>
                            <input type="date" name="end_date" id="end_date" x-model="endDate" value="{{ old('end_date') }}" 
                                   class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-red-500 focus:ring-0 focus:outline-none transition text-sm font-mono" style="color-scheme: dark;">
                            @error('end_date') <p class="text-red-400 text-xs mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_active" value="1" id="is_active" class="w-5 h-5 rounded bg-black border-gray-700 text-lime-500 focus:ring-lime-500 focus:ring-offset-black cursor-pointer" @checked(old('is_active', true))>
                        <label for="is_active" class="ml-3 text-gray-400 text-sm font-bold uppercase tracking-wider cursor-pointer">Сразу активировать</label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <button type="submit" class="bg-red-500 hover:bg-red-400 text-white font-black py-2.5 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                            Создать акцию
                        </button>
                        <a href="{{ route('admin.promotions.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition">Отмена</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/2">
            <h2 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4">Предпросмотр</h2>
            
            <div class="glass-card rounded-xl overflow-hidden group border-l-4 border-red-500/50">
                <div class="p-6">
                    <div class="inline-block bg-red-500/20 border border-red-500/50 text-red-500 text-sm font-black px-3 py-1 rounded-sm mb-4 shadow-[0_0_10px_rgba(239,68,68,0.3)]" x-show="discount > 0">
                        -<span x-text="discount"></span>%
                    </div>
                    
                    <h3 class="text-xl font-black text-white uppercase mb-3 tracking-wide" x-text="title || 'Название акции'"></h3>
                    <p class="text-gray-400 text-sm mb-5 leading-relaxed" x-text="description || 'Описание акции...'"></p>
                    
                    <div class="border-t border-white/5 pt-4 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">
                            До <span class="text-yellow-400" x-text="endDate ? new Date(endDate).toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '--.--.----'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>