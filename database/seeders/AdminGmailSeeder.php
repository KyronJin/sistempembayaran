<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminGmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Admin',
            'password' => Hash::make('12345'),
            'role' => 'admin',
            'phone' => '081234567899',
            'address' => 'Jl. Admin Gmail',
            'is_active' => true,
        ];

        $user = User::firstOrCreate(['email' => 'admin@gmail.com'], $data);

        if (! $user->wasRecentlyCreated) {
            // Ensure role and password are up to date if user already exists
            $user->update($data);
        }

        $this->command?->info('Admin user admin@gmail.com ensured. Password: 12345');
    }
}
