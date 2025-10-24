<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Services\QRCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $qrService = new QRCodeService();

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@sistempembayaran.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'is_active' => true,
        ]);

        // Create Kasir User
        $kasir = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@sistempembayaran.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '081234567891',
            'address' => 'Jl. Kasir No. 1',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Makanan', 'description' => 'Produk makanan'],
            ['name' => 'Minuman', 'description' => 'Produk minuman'],
            ['name' => 'Snack', 'description' => 'Produk snack'],
            ['name' => 'Alat Tulis', 'description' => 'Alat tulis dan perlengkapan sekolah'],
            ['name' => 'Elektronik', 'description' => 'Produk elektronik'],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Sample Products
        $products = [
            [
                'name' => 'Indomie Goreng',
                'sku' => 'INDO-001',
                'barcode' => '8992933000101',
                'category_id' => 1,
                'description' => 'Mie instan rasa goreng',
                'price' => 3000,
                'member_price' => 2800,
                'stock' => 100,
                'min_stock' => 20,
            ],
            [
                'name' => 'Aqua 600ml',
                'sku' => 'AQUA-001',
                'barcode' => '8991002500013',
                'category_id' => 2,
                'description' => 'Air mineral kemasan 600ml',
                'price' => 3500,
                'member_price' => 3300,
                'stock' => 150,
                'min_stock' => 30,
            ],
            [
                'name' => 'Chitato Sapi Panggang',
                'sku' => 'CHIT-001',
                'barcode' => '8992775001011',
                'category_id' => 3,
                'description' => 'Keripik kentang rasa sapi panggang',
                'price' => 10000,
                'member_price' => 9500,
                'stock' => 50,
                'min_stock' => 10,
            ],
            [
                'name' => 'Pulpen Standard AE7',
                'sku' => 'PEN-001',
                'barcode' => '8993401120001',
                'category_id' => 4,
                'description' => 'Pulpen standard warna hitam',
                'price' => 2500,
                'member_price' => 2300,
                'stock' => 80,
                'min_stock' => 15,
            ],
            [
                'name' => 'Baterai AA',
                'sku' => 'BAT-001',
                'barcode' => '8993401130001',
                'category_id' => 5,
                'description' => 'Baterai alkaline AA',
                'price' => 15000,
                'member_price' => 14000,
                'stock' => 60,
                'min_stock' => 15,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Generate QR Code
            $qrPath = $qrService->generateProductQR($product);
            $product->update(['qr_code' => $qrPath]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials: admin@sistempembayaran.test / password');
        $this->command->info('Kasir credentials: kasir@sistempembayaran.test / password');
    }
}
