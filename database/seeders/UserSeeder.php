<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // password otomatis di-hash oleh cast 'hashed' di model User
        User::firstOrCreate(['email' => 'admin@opentune.test'], [
            'username' => 'admin',
            'password' => 'Admin123',
            'role'     => 'admin',
        ]);

        User::firstOrCreate(['email' => 'user@opentune.test'], [
            'username' => 'demo',
            'password' => 'User1234',
            'role'     => 'user',
        ]);
    }
}