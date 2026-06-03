<x-admin-layout>
<div>
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4">Бронирования</h1>
        <a href="{{ route('admin.bookings.create') }}" class="w-full sm:w-auto text-center bg-lime-500 hover:bg-lime-400 text-black px-5 py-2 font-black uppercase text-xs tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.4)]">
            + Создать бронь
        </a>
    </div>

    <div class="glass-card p-5 rounded-xl mb-8">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="space-y-5">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.bookings.index', ['status' => 'Pending']) }}" class="border {{ request('status') === 'Pending' ? 'border-yellow-500/50 bg-yellow-500/20 text-yellow-400' : 'border-white/10 text-gray-500 hover:border-white/30 hover:text-white' }} px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition rounded-md">
                        Ожидают
                    </a>
                    <a href="{{ route('admin.bookings.index', ['date' => now()->format('Y-m-d')]) }}" class="border {{ request('date') === now()->format('Y-m-d') ? 'border-lime-500/50 bg-lime-500/20 text-lime-400' : 'border-white/10 text-gray-500 hover:border-white/30 hover:text-white' }} px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition rounded-md">
                        Сегодня
                    </a>
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Статус</label>
                    <select name="status" class="appearance-none bg-black/50 border border-white/10 text-white h-10 pl-3 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs" style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                        <option value="">Все статусы</option>
                        @foreach ($statuses as $statusOpt)
                            <option value="{{ $statusOpt->value }}" {{ request('status') == $statusOpt->value ? 'selected' : '' }}>{{ $statusOpt->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Дата</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="bg-black/50 border border-white/10 text-white h-10 px-3 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs font-mono" style="color-scheme: dark;">
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Трасса</label>
                    <select name="track_id" class="appearance-none bg-black/50 border border-white/10 text-white h-10 pl-3 pr-8 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs" style="background-image: url('data:image/svg+xml;utf8,<svg fill=\'%239ca3af\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;">
                        <option value="">Все трассы</option>
                        @foreach ($tracks as $track)
                            <option value="{{ $track->id }}" {{ request('track_id') == $track->id ? 'selected' : '' }}>{{ $track->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 items-center h-10 ml-auto">
                    <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black h-10 px-5 uppercase text-[10px] tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.3)]">Применить</button>
                    <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition inline-flex items-center h-full">Сбросить</a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-lime-900/20 border border-lime-500/30 p-4 text-lime-400 font-bold uppercase text-sm tracking-wider backdrop-blur-sm">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
            Бронирований не найдено.
        </div>
    @else
        @foreach($bookings->groupBy(fn($b) => $b->timeSlot->date->format('Y-m-d')) as $date => $dayBookings)
        @php $isPast = \Carbon\Carbon::parse($date)->lt(today()); @endphp
        <div class="mb-8 {{ $isPast ? 'opacity-50' : '' }}">
            <h2 class="text-lg font-black text-white uppercase mb-4 tracking-wider flex items-center border-l-4 {{ $isPast ? 'border-gray-600 pl-3' : 'border-lime-500 pl-3' }}">
                {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM, dddd') }}
                @if(\Carbon\Carbon::parse($date)->isToday())
                    <span class="ml-3 text-[10px] font-black text-lime-400 border border-lime-500/30 px-2 py-0.5 rounded-full">СЕГОДНЯ</span>
                @endif
            </h2>

            <div class="glass-card rounded-xl overflow-x-auto">
                <table class="min-w-full w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10">
                            <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Время / Трасса</th>
                            <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Клиент</th>
                            <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Карты / Участники</th>
                            <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Статус</th>
                            <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-400 text-sm">
                        @foreach ($dayBookings as $booking)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-3 px-5">
                                <div class="font-mono font-bold text-white text-xs">{{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}</div>
                                <div class="text-xs text-gray-500 uppercase font-bold mt-1">{{ $booking->timeSlot->track->name ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-5">
                                <div class="text-white font-bold text-xs">{{ $booking->user->name ?? 'Удален' }}</div>
                                <div class="text-gray-500 text-[10px] font-mono">{{ $booking->user->phone ?? $booking->user->email }}</div>
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="text-white font-bold">{{ $booking->participants_count }} чел.</div>
                                <div class="mt-1 flex flex-wrap gap-1 justify-center">
                                    @foreach($booking->bookingKarts as $bk)
                                        <span class="bg-white/5 border border-white/10 text-gray-400 py-0.5 px-2 rounded text-[10px] font-bold">
                                            {{ $bk->kartType->name ?? 'Тип?' }} x{{ $bk->quantity }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-5 text-center">
                                @if($booking->status == \App\Enums\BookingStatus::Pending)
                                    <span class="bg-yellow-500/20 border border-yellow-500/50 text-yellow-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Ожидает</span>
                                @elseif($booking->status == \App\Enums\BookingStatus::Confirmed)
                                    <span class="bg-lime-500/20 border border-lime-500/50 text-lime-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Подтверждена</span>
                                @elseif($booking->status == \App\Enums\BookingStatus::Completed)
                                    <span class="bg-blue-500/20 border border-blue-500/50 text-blue-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Завершена</span>
                                @elseif($booking->status == \App\Enums\BookingStatus::Cancelled)
                                    <span class="bg-red-500/20 border border-red-500/50 text-red-400 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest line-through">Отменена</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($booking->status == \App\Enums\BookingStatus::Pending)
                                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="border border-lime-500/50 text-lime-400 hover:bg-lime-500 hover:text-black font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">Подтвердить</button>
                                        </form>
                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white font-black py-1.5 px-3 text-[10px] uppercase tracking-widest transition">Отклонить</button>
                                        </form>
                                    @else
                                        <span class="text-gray-600 text-[10px] font-bold uppercase">Обработано</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <div class="mt-8 flex justify-center">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
</x-admin-layout>