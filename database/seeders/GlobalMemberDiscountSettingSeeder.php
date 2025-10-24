<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class GlobalMemberDiscountSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::setValue(
            'global_member_discount_percent',
            5,
            'integer',
            'Default global member discount percent as fallback when product has no member_price'
        );

        $this->command?->info('Setting global_member_discount_percent set to 5');
    }
}
