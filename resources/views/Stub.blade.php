<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title ?? 'Страница в разработке' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center gap-4">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.24-1.79c.051-.023.1-.05.148-.077l-.148.077Zm0 0 .642-.642a1.125 1.125 0 0 0-1.59-1.591l-.643.643M4.5 4.5l15 15" />
                    </svg>
                    <p class="text-lg font-medium">{{ $title ?? 'Страница в разработке' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Этот раздел ещё не реализован. Загляните позже.
                    </p>
                    <a href="{{ url()->previous() === url()->current() ? '/' : url()->previous() }}"
                       class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        ← Вернуться назад
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

