<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@asnenafrica.org'],
            [
                'name' => 'ASNEN Administrator',
                'password' => Hash::make('ChangeMe!ASNEN2024'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['Super Admin']);
    }
}
