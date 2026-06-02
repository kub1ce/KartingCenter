<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-4xl mx-auto">

    <a href="{{ route('bookings.index') }}" class="text-gray-500 hover:text-lime-400 uppercase text-xs font-bold tracking-widest transition mb-8 inline-flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Мои заезды
    </a>

    @php
        $statusColors = [
            'Pending' => ['border' => 'border-yellow-500', 'bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'label' => 'ОЖИДАНИЕ ПОДТВЕРЖДЕНИЯ', 'stroke' => '#facc15'],
            'Confirmed' => ['border' => 'border-lime-500', 'bg' => 'bg-lime-500/20', 'text' => 'text-lime-400', 'label' => 'ЗАЕЗД ПОДТВЕРЖДЕН', 'stroke' => '#a3e635'],
            'Cancelled' => ['border' => 'border-red-500', 'bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'label' => 'ЗАЕЗД ОТМЕНЕН', 'stroke' => '#ef4444'],
            'Completed' => ['border' => 'border-gray-500', 'bg' => 'bg-gray-500/20', 'text' => 'text-gray-400', 'label' => 'ЗАЕЗД ЗАВЕРШЕН', 'stroke' => '#6b7280'],
        ];
        $statusKey = $booking->status->value;
        if ($statusKey === 'Confirmed' && $booking->timeSlot->date < today()) $statusKey = 'Completed';
        $s = $statusColors[$statusKey] ?? $statusColors['Pending'];
        
        $trackColors = [
            'Easy' => 'text-lime-400',
            'Medium' => 'text-yellow-400',
            'Hard' => 'text-red-400',
        ];
        $trackColor = $trackColors[$booking->timeSlot->track->difficulty->name] ?? 'text-white';
    @endphp

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <span class="text-3xl font-black text-white font-mono tracking-tight">#{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
                <span class="w-2 h-2 rounded-full {{ $s['text'] }} bg-current animate-pulse"></span>
            </div>
            <p class="text-xs {{ $s['text'] }} font-bold uppercase tracking-widest">{{ $s['label'] }}</p>
        </div>
        
        @if($booking->status->value === 'Pending' || $booking->status->value === 'Confirmed')
            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите отменить заезд?');">
                @csrf @method('PATCH')
                <button type="submit" class="border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white px-6 py-2 font-black uppercase text-xs tracking-widest transition">
                    Отменить заезд
                </button>
            </form>
        @endif
    </div>

    <div class="glass-card rounded-xl p-6 md:p-8 mb-6 border-t-2 {{ $s['border'] }}">
        <h3 class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Маршрут и время</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-600 uppercase tracking-wider mb-1">Трасса</p>
                <p class="text-xl font-black {{ $trackColor }} uppercase">{{ $booking->timeSlot->track->name }}</p>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">{{ $booking->timeSlot->track->difficulty->name }} DIFFICULTY / {{ $booking->timeSlot->track->length }}м</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 uppercase tracking-wider mb-1">Дата и время</p>
                <p class="text-lg font-black text-white uppercase">{{ $booking->timeSlot->date->isoFormat('D MMMM, dddd') }}</p>
                <p class="font-mono text-2xl font-black text-white tracking-wider mt-1">
                    {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                </p>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-xl p-6 md:p-8 mb-6">
        <h3 class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Состав заезда</h3>
        
        <div class="space-y-4">
            @foreach($booking->bookingKarts as $bk)
                <div class="flex items-center justify-between bg-black/30 p-4 rounded-lg border border-white/5">
                    <div>
                        <p class="text-white font-bold uppercase text-sm">{{ $bk->kartType->name }} карт × {{ $bk->quantity }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Коэффициент: <span class="text-gray-400 font-mono">{{ $bk->kartType->price_modifier }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        @php 
                            $linePrice = $booking->timeSlot->track->price_per_slot * $bk->kartType->price_modifier * $bk->quantity;
                        @endphp
                        <p class="text-white font-bold font-mono">{{ number_format($linePrice, 0, ',', ' ') }} ₽</p>
                    </div>
                </div>
            @endforeach
            
            <div class="flex items-center justify-between pt-2">
                <p class="text-gray-400 font-bold uppercase text-sm">Общее количество участников</p>
                <p class="text-white font-bold text-lg">{{ $booking->participants_count }}</p>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-xl p-6 md:p-8 border-l-4 {{ $s['border'] }} bg-black/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Итого к оплате</p>
                <p class="text-4xl font-black text-white tracking-tight">{{ number_format($booking->total_price, 0, ',', ' ') }} <span class="text-xl text-gray-500">₽</span></p>
            </div>
            <div class="text-right text-xs text-gray-600 font-bold uppercase tracking-widest">
                Бронь создана<br>
                {{ $booking->created_at->isoFormat('D MMM YYYY, HH:mm') }}
            </div>
        </div>
    </div>

</div>
</x-app-layout>