<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'points_conversion_rate',
                'value' => '10000', // Rp 10.000 = 1 poin
                'type' => 'integer',
                'description' => 'Jumlah rupiah untuk mendapatkan 1 poin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'points_to_rupiah',
                'value' => '1000', // 1 poin = Rp 1.000
                'type' => 'integer',
                'description' => 'Nilai 1 poin dalam rupiah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tax_rate',
                'value' => '11', // PPN 11%
                'type' => 'integer',
                'description' => 'Persentase pajak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_name',
                'value' => 'Toko Saya',
                'type' => 'string',
                'description' => 'Nama toko',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_address',
                'value' => 'Jl. Contoh No. 123',
                'type' => 'string',
                'description' => 'Alamat toko',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_phone',
                'value' => '081234567890',
                'type' => 'string',
                'description' => 'Nomor telepon toko',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
