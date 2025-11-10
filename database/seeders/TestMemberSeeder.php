<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Create user with member role
        $user = User::create([
            'name' => 'Test Member',
            'email' => 'member@test.com',
            'password' => Hash::make('12345'),
            'role' => 'member',
            'phone' => '081234567892',
            'address' => 'Jl. Test Member No. 1, Jakarta Selatan',
            'is_active' => true,
        ]);

        // Create member profile
        Member::create([
            'user_id' => $user->id,
            'member_code' => 'MBR' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            'points' => 100,
            'join_date' => now(),
            'status' => 'active',
        ]);

        $this->command->info('✅ Test member created successfully!');
        $this->command->info('');
        $this->command->info('📧 Email: member@test.com');
        $this->command->info('🔑 Password: 12345');
        $this->command->info('💰 Points: 100');
        $this->command->info('✨ Status: Active');
        $this->command->info('');
        $this->command->info('🌐 Login at: http://127.0.0.1:8000/member/login');
    }
}
