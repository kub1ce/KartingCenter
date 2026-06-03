<x-admin-layout>
<div>
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4">Управление картами</h1>
        <a href="{{ route('admin.karts.create') }}" class="w-full sm:w-auto text-center bg-lime-500 hover:bg-lime-400 text-black px-5 py-2 font-black uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
            + Добавить карт
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
                        <th class="py-4 px-6 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Номер</th>
                        <th class="py-4 px-6 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Тип</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Статус</th>
                        <th class="py-4 px-6 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Действия</th>
                    </tr>
                </thead>
                <tbody class="text-gray-400 text-sm">
                    @foreach ($karts as $kart)
                    @php
                        $statusConfig = [
                            'Available' => ['class' => 'bg-lime-500/20 border-lime-500/50 text-lime-400', 'label' => 'СВОБОДЕН'],
                            'Maintenance' => ['class' => 'bg-red-500/20 border-red-500/50 text-red-400', 'label' => 'НА ТО'],
                            'Reserved' => ['class' => 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400', 'label' => 'ЗАНЯТ'],
                        ];
                        $s = $statusConfig[$kart->status->name] ?? $statusConfig['Available'];
                    @endphp
                    <tr class="border-b border-white/5 hover:bg-lime-500/5 transition-colors">
                        <td class="py-4 px-6 text-left whitespace-nowrap">
                            <span class="text-white font-black font-mono text-lg tracking-widest">#{{ $kart->number }}</span>
                        </td>
                        <td class="py-4 px-6 text-left whitespace-nowrap font-bold text-gray-300">
                            {{ $kart->kartType->name ?? 'Не указан' }}
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <span class="{{ $s['class'] }} border py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $s['label'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.karts.edit', $kart->id) }}" class="border border-yellow-500/50 text-yellow-400 hover:bg-yellow-500 hover:text-black font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">
                                    Ред.
                                </a>
                                <form action="{{ route('admin.karts.destroy', $kart->id) }}" method="POST" onsubmit="return confirm('Удалить карт?');">
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
        {{ $karts->links() }}
    </div>
</div>
</x-admin-layout>