<x-app-layout>
<div class="cyber-grid min-h-screen px-4 sm:px-6 lg:px-8 py-10 max-w-7xl mx-auto">

    <h1 class="text-2xl font-black text-white uppercase mb-8 tracking-wider border-l-4 border-lime-500 pl-4">Мои заезды</h1>

    <div x-data="{ tab: '{{ $tab ?? 'upcoming' }}' }">
        
        <div class="mb-8 border-b border-white/5">
            <div class="flex space-x-8">
                <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'border-lime-500 text-lime-400' : 'border-transparent text-gray-500 hover:text-white'" class="py-3 text-sm font-black uppercase tracking-widest border-b-2 transition">
                    Предстоящие ({{ $upcoming->count() }})
                </button>
                <button @click="tab = 'history'" :class="tab === 'history' ? 'border-lime-500 text-lime-400' : 'border-transparent text-gray-500 hover:text-white'" class="py-3 text-sm font-black uppercase tracking-widest border-b-2 transition">
                    История ({{ $history->count() }})
                </button>
            </div>
        </div>

        <div x-show="tab === 'upcoming'" x-transition>
            @if($upcoming->isEmpty())
                <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
                    У вас нет предстоящих заездов. <a href="{{ route('schedule.index') }}" class="text-lime-400 hover:underline">Забронировать!</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($upcoming as $booking)
                        @php
                            $statusColors = [
                                'Pending' => ['border' => 'border-yellow-500', 'bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'label' => 'ОЖИДАНИЕ'],
                                'Confirmed' => ['border' => 'border-lime-500', 'bg' => 'bg-lime-500/20', 'text' => 'text-lime-400', 'label' => 'ПОДТВЕРЖДЕНО'],
                            ];
                            $s = $statusColors[$booking->status->value] ?? $statusColors['Pending'];
                            
                            $plural = function ($n, $form1, $form2, $form5) {
                                $n = abs((int) $n) % 100;
                                $n1 = $n % 10;
                                if ($n > 10 && $n < 20) return $form5;
                                if ($n1 > 1 && $n1 < 5) return $form2;
                                if ($n1 == 1) return $form1;
                                return $form5;
                            };

                            $timeToStart = '';
                            $iconType = 'clock';
                            $isLiveNow = false;
                            
                            $startTime = \Carbon\Carbon::parse($booking->timeSlot->date->toDateString() . ' ' . $booking->timeSlot->start_time);
                            $endTime = \Carbon\Carbon::parse($booking->timeSlot->date->toDateString() . ' ' . $booking->timeSlot->end_time);
                            $now = now();

                            if ($now >= $startTime && $now <= $endTime) {
                                $timeToStart = "ИДЕТ ЗАЕЗД";
                                $iconType = 'live';
                                $isLiveNow = true;
                            } elseif ($now < $startTime) {
                                $diffInMinutes = (int) $startTime->diffInMinutes($now);
                                
                                $hours = floor($diffInMinutes / 60);
                                $minutes = $diffInMinutes % 60;
                                
                                $timeStr = '';
                                if ($hours > 0) {
                                    $timeStr .= $hours . ' ' . $plural($hours, 'час', 'часа', 'часов');
                                }
                                if ($minutes > 0) {
                                    $timeStr .= ($hours > 0 ? ' ' : '') . $minutes . ' ' . $plural($minutes, 'минуту', 'минуты', 'минут');
                                }

                                if ($booking->timeSlot->date->isToday()) {
                                    if ($diffInMinutes <= 180) {
                                        $timeToStart = "Через $timeStr";
                                        $iconType = 'fire';
                                    } else {
                                        $timeToStart = "Сегодня через $timeStr";
                                    }
                                } elseif ($booking->timeSlot->date->isTomorrow()) {
                                    $timeToStart = 'Завтра';
                                } else {
                                    $daysLeft = now()->diffInDays($booking->timeSlot->date);
                                    $timeToStart = "Через $daysLeft " . $plural($daysLeft, 'день', 'дня', 'дней');
                                }
                            } else {
                                $timeToStart = "Завершен";
                            }
                        @endphp
                        
                        <a href="{{ route('bookings.show', $booking) }}" class="group block">
                            <div class="glass-card rounded-xl overflow-hidden border-l-4 {{ $isLiveNow ? 'border-orange-500 shadow-[0_0_25px_rgba(249,115,22,0.3)]' : $s['border'] }} hover:shadow-[0_0_15px_rgba(163,230,53,0.1)] transition-all duration-300 {{ $isLiveNow ? 'mt-6' : '' }}">
                                
                                @if($isLiveNow)
                                    <div class="bg-orange-500/10 border-b border-orange-500/30 px-5 py-2 flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                                        <span class="text-orange-400 text-[10px] font-black uppercase tracking-widest">LIVE</span>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row">
                                    <div class="flex-1 p-5">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="inline-flex items-center {{ $isLiveNow ? 'bg-orange-500/20 text-orange-400' : $s['bg'] . ' ' . $s['text'] }} text-[10px] font-black px-2 py-0.5 rounded tracking-widest uppercase">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isLiveNow ? 'bg-orange-500' : $s['text'] . ' bg-current' }} mr-1.5 {{ $isLiveNow ? 'animate-pulse' : '' }}"></span>
                                                {{ $isLiveNow ? 'ИДЕТ ЗАЕЗД' : $s['label'] }}
                                            </span>
                                            <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest font-mono">#{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        
                                        <h3 class="text-lg font-black text-white uppercase mb-1 tracking-wide">{{ $booking->timeSlot->track->name }}</h3>
                                        
                                        <div class="flex items-center space-x-2 mb-4">
                                            @if($iconType === 'live')
                                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"></circle></svg>
                                            @elseif($iconType === 'fire')
                                                <svg class="w-4 h-4 {{ $s['text'] }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 {{ $s['text'] }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                            <p class="text-sm {{ $isLiveNow ? 'text-orange-400' : $s['text'] }} font-black uppercase tracking-wider {{ $iconType === 'live' || $iconType === 'fire' ? 'animate-pulse' : '' }}">{{ $timeToStart }}</p>
                                            <span class="text-xs text-gray-600">({{ $booking->timeSlot->date->isoFormat('D MMM') }})</span>
                                        </div>

                                        <div class="font-mono text-xl font-black text-white tracking-wider mb-3">
                                            {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach($booking->bookingKarts as $bk)
                                                <span class="bg-gray-800 border border-white/5 text-gray-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                                                    {{ $bk->kartType->name }} ×{{ $bk->quantity }}
                                                </span>
                                            @endforeach
                                            <span class="bg-gray-800 border border-white/5 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                                                Участники: {{ $booking->participants_count }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="sm:w-1/3 border-t sm:border-t-0 sm:border-l border-white/5 bg-black/20 p-5 flex flex-col justify-between items-center text-center">
                                        <div>
                                            <p class="text-[10px] text-gray-600 font-bold uppercase tracking-widest mb-1">Итого</p>
                                            <p class="text-2xl font-black text-white">{{ number_format($booking->total_price, 0, ',', ' ') }} <span class="text-sm text-gray-500">₽</span></p>
                                        </div>
                                        
                                        <div class="mt-4 w-full">
                                            @if($booking->status->value === 'Pending' || $booking->status->value === 'Confirmed')
                                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onclick="event.stopPropagation();" onsubmit="return confirm('Вы уверены, что хотите отменить заезд?');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="w-full border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white px-3 py-1.5 font-black uppercase text-[10px] tracking-wider transition">
                                                        Отменить
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div x-show="tab === 'history'" x-transition>
            @if($history->isEmpty())
                <div class="glass-card rounded-xl p-6 text-center text-gray-500 font-bold uppercase tracking-widest">
                    История пуста
                </div>
            @else
                <div class="space-y-4">
                    @foreach($history as $booking)
                        @php
                            $statusColors = [
                                'Completed' => ['border' => 'border-gray-700', 'bg' => 'bg-gray-700/20', 'text' => 'text-gray-400', 'label' => 'ЗАВЕРШЕНО'],
                                'Cancelled' => ['border' => 'border-red-900', 'bg' => 'bg-red-900/20', 'text' => 'text-red-500', 'label' => 'ОТМЕНЕНО'],
                            ];
                            $statusKey = $booking->status->value;
                            if ($statusKey === 'Confirmed' || $statusKey === 'Pending') $statusKey = 'Completed';
                            $s = $statusColors[$statusKey] ?? $statusColors['Completed'];
                        @endphp
                        
                        <a href="{{ route('bookings.show', $booking) }}" class="group block opacity-70 hover:opacity-100 transition-opacity">
                            <div class="glass-card rounded-xl overflow-hidden border-l-4 {{ $s['border'] }}">
                                <div class="flex flex-col sm:flex-row">
                                    <div class="flex-1 p-5">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="inline-flex items-center {{ $s['bg'] }} {{ $s['text'] }} text-[10px] font-black px-2 py-0.5 rounded tracking-widest uppercase">
                                                {{ $s['label'] }}
                                            </span>
                                            <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest font-mono">#{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <h3 class="text-lg font-black text-gray-400 uppercase mb-1 tracking-wide">{{ $booking->timeSlot->track->name }}</h3>
                                        <p class="text-xs text-gray-600 font-bold uppercase tracking-wider mb-4">{{ $booking->timeSlot->date->isoFormat('D MMMM YYYY') }}</p>
                                        <div class="font-mono text-xl font-black text-gray-500 tracking-wider">
                                            {{ \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') }}
                                        </div>
                                    </div>
                                    <div class="sm:w-1/3 border-t sm:border-t-0 sm:border-l border-white/5 bg-black/20 p-5 flex flex-col justify-center items-center text-center">
                                        <p class="text-2xl font-black text-gray-500">{{ number_format($booking->total_price, 0, ',', ' ') }} <span class="text-sm text-gray-600">₽</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
</x-app-layout>