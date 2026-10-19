<?php

namespace Database\Seeders;

use App\Models\User;        // ← SIGURADUHING NANDIYAN ITO
use App\Models\Account;     // ← ITO ANG KULANG!
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Gumawa ng test user kung wala pa
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
            ]
        );

        // Gumawa ng sample account
        Account::create([
            'user_id' => $user->id,
            'account_number' => 'ACC-100001',
            'balance' => 5000.00,
            'minimum_balance' => 100.00,
        ]);

        Account::create([
            'user_id' => $user->id,
            'account_number' => 'ACC-100002',
            'balance' => 15000.00,
            'minimum_balance' => 500.00,
        ]);
    }
}