<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('schedule.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.*')">
                        {{ __('Расписание') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tracks.index')" :active="request()->routeIs('tracks.*')">
                        {{ __('Трассы') }}
                    </x-nav-link>

                    @auth
                        @can('is-client')
                            <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                                {{ __('Мои брони') }}
                            </x-nav-link>
                        @endcan
                        @can('is-content-manager')
                            <x-nav-link :href="route('content.news.index')" :active="request()->routeIs('content.news.*')">
                                {{ __('Новости') }}
                            </x-nav-link>
                            <x-nav-link :href="route('content.promotions.index')" :active="request()->routeIs('content.promotions.*')">
                                {{ __('Акции') }}
                            </x-nav-link>
                        @endcan

                        @can('is-admin')
                            <x-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                                {{ __('Все брони') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Пользователи') }}
                            </x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-600">
                                {{ Auth::user()->role_id->label() }}
                            </div>

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Выйти') }}
                            </x-dropdown-link>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                        {{ __('Войти') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        {{ __('Регистрация') }}
                    </a>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('schedule.index')" :active="request()->routeIs('schedule.*')">
                {{ __('Расписание') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tracks.index')" :active="request()->routeIs('tracks.*')">
                {{ __('Трассы') }}
            </x-responsive-nav-link>

            @auth
                @can('is-client')
                    <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                        {{ __('Мои брони') }}
                    </x-responsive-nav-link>
                @endcan

                @can('is-content-manager')
                    <x-responsive-nav-link :href="route('content.news.index')">{{ __('Новости') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('content.promotions.index')">{{ __('Акции') }}</x-responsive-nav-link>
                @endcan

                @can('is-admin')
                    <x-responsive-nav-link :href="route('admin.bookings.index')">{{ __('Все брони') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')">{{ __('Пользователи') }}</x-responsive-nav-link>
                @endcan
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ Auth::user()->role_id->label() }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        {{ __('Выйти') }}
                    </x-responsive-nav-link>
                    <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-600 space-y-1 px-4">
                <x-responsive-nav-link :href="route('login')">{{ __('Войти') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">{{ __('Регистрация') }}</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
