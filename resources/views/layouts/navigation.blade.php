<nav x-data="{ open: false }" class="bg-black/60 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="text-2xl font-black tracking-widest text-red-500 uppercase" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);">
                        KART<span class="text-white">.CENTER</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center uppercase text-sm font-bold tracking-wider">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        Главная
                    </x-nav-link>
                    <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.*')">
                        Расписание
                    </x-nav-link>
                    <x-nav-link :href="route('tracks.index')" :active="request()->routeIs('tracks.*')">
                        Трассы
                    </x-nav-link>
                    <x-nav-link :href="route('public.news.index')" :active="request()->routeIs('public.news.*')">
                        Новости
                    </x-nav-link>
                    <x-nav-link :href="route('public.promotions.index')" :active="request()->routeIs('public.promotions.*')">
                        Акции
                    </x-nav-link>

                    @auth
                        @can('is-client')
                            <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                                Мои брони
                            </x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4 uppercase text-sm font-bold tracking-wider">
                @auth
                    @canany(['is-admin', 'is-content-manager'])
                        <a href="{{ route('admin.news.index') }}" class="border border-lime-500/50 text-lime-400 hover:bg-lime-500 hover:text-black px-4 py-1.5 font-black transition text-xs hover:shadow-[0_0_15px_rgba(163,230,53,0.3)]">
                            Админ-панель
                        </a>
                    @endcanany

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-lime-400 transition focus:outline-none">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-red-500 font-bold uppercase tracking-widest border-b border-white/5">
                                {{ Auth::user()->role_id->label() }}
                            </div>

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Выйти
                            </x-dropdown-link>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-lime-400 transition">
                        Войти
                    </a>
                    <a href="{{ route('register') }}" class="border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white px-5 py-1.5 font-black uppercase tracking-wider transition text-xs hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                        Регистрация
                    </a>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-lime-400 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- МОБИЛЬНОЕ МЕНЮ -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">Главная</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.*')">Расписание</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tracks.index')" :active="request()->routeIs('tracks.*')">Трассы</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('public.news.index')" :active="request()->routeIs('public.news.*')">Новости</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('public.promotions.index')" :active="request()->routeIs('public.promotions.*')">Акции</x-responsive-nav-link>

            @auth
                @can('is-client')
                    <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                        Мои брони
                    </x-responsive-nav-link>
                @endcan

                @canany(['is-admin', 'is-content-manager'])
                    <x-responsive-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.*')">
                        Админ-панель
                    </x-responsive-nav-link>
                @endcanany
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-white/5">
                <div class="px-4">
                    <div class="font-medium text-base text-white uppercase font-black">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-red-500 mt-1 font-bold uppercase tracking-widest">{{ Auth::user()->role_id->label() }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        Выйти
                    </x-responsive-nav-link>
                    <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-white/5 space-y-1 px-4">
                <x-responsive-nav-link :href="route('login')">Войти</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Регистрация</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>