<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Администратор',
                'email' => 'admin@karting.local',
                'phone' => '+7 900 000 00 00',
                'password' => Hash::make('password'),
                'role_id' => Role::Administrator->value,
            ],
            [
                'name' => 'Контент-менеджер',
                'email' => 'content@karting.local',
                'phone' => '+7 900 000 00 01',
                'password' => Hash::make('password'),
                'role_id' => Role::ContentManager->value,
            ],
            [
                'name' => 'Тестовый клиент',
                'email' => 'client@karting.local',
                'phone' => '+7 900 000 00 02',
                'password' => Hash::make('password'),
                'role_id' => Role::User->value,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
