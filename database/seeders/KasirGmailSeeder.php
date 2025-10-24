<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KasirGmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Kasir',
            'password' => Hash::make('12345'),
            'role' => 'kasir',
            'phone' => '081234567888',
            'address' => 'Jl. Kasir Gmail',
            'is_active' => true,
        ];

        $user = User::firstOrCreate(['email' => 'kasir@gmail.com'], $data);

        if (! $user->wasRecentlyCreated) {
            // Ensure role and password are up to date if user already exists
            $user->update($data);
        }

        $this->command?->info('Kasir user kasir@gmail.com ensured. Password: 12345');
    }
}
