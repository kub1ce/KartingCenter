<x-admin-layout>
<div>
    <a href="{{ route('admin.slots.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-6 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Все слоты
    </a>

    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4 mb-8">Генератор расписания</h1>

    <div class="glass-card p-6 rounded-xl max-w-4xl">
        <p class="text-gray-500 text-xs uppercase tracking-widest mb-6 leading-relaxed border-b border-white/5 pb-4">
            Выберите период, дни недели, нужные трассы и временные интервалы. Система создаст слоты только для выбранных комбинаций. Дубликаты созданы не будут.
        </p>

        <form action="{{ route('admin.slots.generate.store') }}" method="POST">
            @csrf

            <div class="flex gap-4 mb-6">
                <div class="w-1/2">
                    <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Дата начала</label>
                    <input type="date" name="date_from" required value="{{ old('date_from', today()->addDay()->format('Y-m-d')) }}" 
                           class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono" style="color-scheme: dark;">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-500 text-[10px] font-bold mb-2 uppercase tracking-widest">Дата окончания</label>
                    <input type="date" name="date_to" required value="{{ old('date_to', today()->addDays(7)->format('Y-m-d')) }}" 
                           class="w-full bg-black/50 border border-white/10 text-white py-3 px-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-sm font-mono" style="color-scheme: dark;">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-500 text-[10px] font-bold mb-3 uppercase tracking-widest">Дни недели</label>
                <div class="flex flex-wrap gap-3">
                    @php $days = [1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс']; @endphp
                    @foreach ($days as $num => $name)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="days[]" value="{{ $num }}" class="hidden peer" {{ in_array($num, (array) old('days', [1,2,3,4,5,6,7])) ? 'checked' : '' }}>
                            <div class="border border-gray-700 text-gray-500 px-4 py-2 font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500/20 peer-checked:border-lime-500/50 peer-checked:text-lime-400 hover:border-white hover:text-white">
                                {{ $name }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-500 text-[10px] font-bold mb-3 uppercase tracking-widest">Трассы</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($tracks as $track)
                        @php
                            $cTrack = [
                                'Easy' => ['checked_bg' => 'peer-checked:bg-lime-500/20', 'checked_border' => 'peer-checked:border-lime-500/50', 'checked_text' => 'peer-checked:text-lime-400'],
                                'Medium' => ['checked_bg' => 'peer-checked:bg-yellow-500/20', 'checked_border' => 'peer-checked:border-yellow-500/50', 'checked_text' => 'peer-checked:text-yellow-400'],
                                'Hard' => ['checked_bg' => 'peer-checked:bg-red-500/20', 'checked_border' => 'peer-checked:border-red-500/50', 'checked_text' => 'peer-checked:text-red-400'],
                            ][$track->difficulty->name] ?? [];
                        @endphp
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tracks[]" value="{{ $track->id }}" class="hidden peer" {{ in_array($track->id, (array) old('tracks')) ? 'checked' : '' }}>
                            <div class="border border-gray-700 text-gray-500 px-4 py-2 font-bold uppercase text-xs tracking-widest transition {{ $cTrack['checked_bg'] ?? '' }} {{ $cTrack['checked_border'] ?? '' }} {{ $cTrack['checked_text'] ?? '' }} hover:border-white hover:text-white">
                                {{ $track->name }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-500 text-[10px] font-bold mb-3 uppercase tracking-widest">Временные слоты</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach ($timeSlots as $timeRange)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="times[]" value="{{ $timeRange }}" class="hidden peer" {{ in_array($timeRange, (array) old('times')) ? 'checked' : '' }}>
                            <div class="border border-gray-700 text-gray-500 px-4 py-2 font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500/20 peer-checked:border-lime-500/50 peer-checked:text-lime-400 hover:border-white hover:text-white text-center font-mono">
                                {{ str_replace('-', ' – ', $timeRange) }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black py-2.5 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
                    Сгенерировать
                </button>
                <a href="{{ route('admin.slots.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition">Отмена</a>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>