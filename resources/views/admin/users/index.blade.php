<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Пользователи</h1>

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Имя</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Телефон</th>
                    <th class="py-3 px-6 text-center">Роль</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($users as $user)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $user->id }}</td>
                    <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                    <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                    <td class="py-3 px-6 text-left">{{ $user->phone ?? '—' }}</td>
                    <td class="py-3 px-6 text-center">
                        @php
                            $colorClass = 'bg-gray-200 text-gray-600';
                            if ($user->role_id == \App\Enums\Role::Administrator) $colorClass = 'bg-red-200 text-red-600';
                            if ($user->role_id == \App\Enums\Role::ContentManager) $colorClass = 'bg-blue-200 text-blue-600';
                            if ($user->role_id == \App\Enums\Role::User) $colorClass = 'bg-green-200 text-green-600';
                        @endphp
                        <span class="{{ $colorClass }} py-1 px-3 rounded-full text-xs">
                            {{ $user->role_id->label() }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
</x-admin-layout>