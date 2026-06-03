<x-guest-layout>
    <div class="mb-8 text-center">
        <a href="{{ route('welcome') }}" class="text-3xl font-black tracking-widest text-red-500 uppercase inline-block" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);">
            KART<span class="text-white">.CENTER</span>
        </a>
        <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-2 font-bold">Создание аккаунта</p>
    </div>

    <div class="w-full max-w-xl glass-card p-8 md:p-10 rounded-xl border-l-4 border-lime-500/50 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-24 h-24 bg-lime-500/5 blur-2xl pointer-events-none"></div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                    Имя
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="appearance-none bg-black/70 border border-white/10 text-white h-12 px-4 rounded-md focus:border-lime-500 focus:ring-1 focus:ring-lime-500/50 focus:outline-none transition text-sm w-full font-mono placeholder-gray-600"
                       placeholder="Иван Петров">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400 text-[10px] font-bold uppercase" />
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                    Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="appearance-none bg-black/70 border border-white/10 text-white h-12 px-4 rounded-md focus:border-lime-500 focus:ring-1 focus:ring-lime-500/50 focus:outline-none transition text-sm w-full font-mono placeholder-gray-600"
                       placeholder="driver@karting.ru">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-[10px] font-bold uppercase" />
            </div>

            <div x-data="{ 
                phone: '{{ old('phone') }}',
                formatPhone() {
                    if (!this.phone) return;
                    let val = this.phone;

                    if (val === '8' || val === '7') {
                        this.phone = '+7';
                    }
                    else if (/^[2-9]$/.test(val)) {
                        this.phone = '+7' + val;
                    }
                    else if (val.startsWith('8') && val.length > 1 && !val.startsWith('+')) {
                        this.phone = '+7' + val.substring(1);
                    }
                    else if (val.startsWith('7') && val.length > 1 && !val.startsWith('+')) {
                        this.phone = '+' + val;
                    }
                }
            }">
                <label for="phone" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                    Телефон
                </label>
                <input id="phone" type="tel" x-model="phone" @input="formatPhone()" name="phone" required autocomplete="tel"
                       class="appearance-none bg-black/70 border border-white/10 text-white h-12 px-4 rounded-md focus:border-lime-500 focus:ring-1 focus:ring-lime-500/50 focus:outline-none transition text-sm w-full font-mono placeholder-gray-600"
                       placeholder="+7 (999) 123-45-67">
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-400 text-[10px] font-bold uppercase" />
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                    Пароль
                </label>
                <div class="relative">
                    <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                           class="appearance-none bg-black/70 border border-white/10 text-white h-12 px-4 pr-10 rounded-md focus:border-lime-500 focus:ring-1 focus:ring-lime-500/50 focus:outline-none transition text-sm w-full font-mono placeholder-gray-600"
                           placeholder="Минимум 8 символов">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-lime-400 transition">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-[10px] font-bold uppercase" />
            </div>

            <div x-data="{ showConfirm: false }">
                <label for="password_confirmation" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                    Подтвердите пароль
                </label>
                <div class="relative">
                    <input id="password_confirmation" x-bind:type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                           class="appearance-none bg-black/70 border border-white/10 text-white h-12 px-4 pr-10 rounded-md focus:border-lime-500 focus:ring-1 focus:ring-lime-500/50 focus:outline-none transition text-sm w-full font-mono placeholder-gray-600"
                           placeholder="Повторите пароль">
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-lime-400 transition">
                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showConfirm" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400 text-[10px] font-bold uppercase" />
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-lime-500 hover:bg-lime-400 text-black font-black h-12 px-6 uppercase text-xs tracking-widest transition hover:shadow-[0_0_20px_rgba(163,230,53,0.5)]">
                    Зарегистрироваться
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">
            Уже есть аккаунт? 
            <a href="{{ route('login') }}" class="text-lime-400 hover:text-lime-300 hover:underline transition">
                Войти
            </a>
        </p>
    </div>
</x-guest-layout>