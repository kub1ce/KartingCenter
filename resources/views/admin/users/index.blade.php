<x-admin-layout>
<div>
    <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider border-l-4 border-lime-500 pl-4 mb-6">Пользователи</h1>

    <div class="glass-card p-5 rounded-xl mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-5">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Поиск</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя, email или телефон..." 
                               class="w-full bg-black/50 border border-white/10 text-white h-10 pl-10 pr-4 rounded-md focus:border-lime-500 focus:ring-0 focus:outline-none transition text-xs">
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Роль</label>
                    <div class="flex gap-2">
                        @php $currentRole = request('role'); @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="" class="hidden peer" @if(!$currentRole) checked @endif>
                            <div class="border border-white/10 text-gray-500 px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition peer-checked:bg-white/10 peer-checked:border-white/30 peer-checked:text-white hover:border-white/30 hover:text-white rounded-md">
                                Все
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="1" class="hidden peer" @if($currentRole == '1') checked @endif>
                            <div class="border border-white/10 text-gray-500 px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition peer-checked:bg-lime-500/20 peer-checked:border-lime-500/50 peer-checked:text-lime-400 hover:border-white/30 hover:text-white rounded-md">
                                Клиенты
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="2" class="hidden peer" @if($currentRole == '2') checked @endif>
                            <div class="border border-white/10 text-gray-500 px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition peer-checked:bg-yellow-500/20 peer-checked:border-yellow-500/50 peer-checked:text-yellow-400 hover:border-white/30 hover:text-white rounded-md">
                                Менеджеры
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="0" class="hidden peer" @if($currentRole == '0') checked @endif>
                            <div class="border border-white/10 text-gray-500 px-4 h-10 flex items-center font-bold uppercase text-xs tracking-widest transition peer-checked:bg-red-500/20 peer-checked:border-red-500/50 peer-checked:text-red-400 hover:border-white/30 hover:text-white rounded-md">
                                Админы
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 items-center h-10 ml-auto">
                    <button type="submit" class="bg-lime-500 hover:bg-lime-400 text-black font-black h-10 px-5 uppercase text-[10px] tracking-widest transition hover:shadow-[0_0_15px_rgba(163,230,53,0.3)]">Найти</button>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-white text-xs font-bold uppercase tracking-widest transition inline-flex items-center h-full">Сбросить</a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-lime-900/20 border border-lime-500/30 p-4 text-lime-400 font-bold uppercase text-sm tracking-wider backdrop-blur-sm">{{ session('success') }}</div>
    @endif

    <div class="glass-card rounded-xl overflow-x-auto">
        <table class="min-w-full w-full whitespace-nowrap">
            <thead>
                <tr class="bg-white/5 border-b border-white/10">
                    <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                    <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Пользователь</th>
                    <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Контакты</th>
                    <th class="py-3 px-5 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Роль</th>
                    <th class="py-3 px-5 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Регистрация</th>
                </tr>
            </thead>
            <tbody class="text-gray-400 text-sm">
                @foreach ($users as $user)
                @php 
                    $roleColors = [
                        0 => 'bg-red-500/20 border-red-500/50 text-red-400',
                        1 => 'bg-lime-500/20 border-lime-500/50 text-lime-400',
                        2 => 'bg-yellow-500/20 border-yellow-500/50 text-yellow-400'
                    ];
                    $roleClass = $roleColors[$user->role_id->value ?? $user->role_id] ?? 'bg-gray-500/20 border-gray-500/50 text-gray-400';
                @endphp
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                    <td class="py-3 px-5 font-mono text-gray-600 text-xs">#{{ $user->id }}</td>
                    <td class="py-3 px-5">
                        <div class="font-bold text-white text-xs uppercase">{{ $user->name }}</div>
                    </td>
                    <td class="py-3 px-5">
                        <div class="text-xs font-mono">{{ $user->email }}</div>
                        @if($user->phone)
                            <div class="text-[10px] text-gray-500 font-mono mt-0.5">{{ $user->phone }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-5 text-center">
                        <span class="{{ $roleClass }} border py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {{ $user->role_id->label() }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-xs text-gray-500 font-mono">
                        {{ $user->created_at->format('d.m.Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $users->links() }}
    </div>
</div>
</x-admin-layout>