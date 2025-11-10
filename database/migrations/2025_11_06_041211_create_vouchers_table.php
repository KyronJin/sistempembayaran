<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode voucher unik
            $table->string('name'); // Nama voucher
            $table->text('description')->nullable(); // Deskripsi voucher
            $table->integer('points_required'); // Poin yang dibutuhkan untuk redeem
            $table->enum('discount_type', ['percentage', 'fixed']); // Tipe diskon
            $table->decimal('discount_value', 10, 2); // Nilai diskon
            $table->decimal('min_transaction', 10, 2)->default(0); // Minimum transaksi
            $table->decimal('max_discount', 10, 2)->nullable(); // Max potongan (untuk percentage)
            $table->date('valid_from'); // Mulai berlaku
            $table->date('valid_until'); // Berakhir
            $table->integer('max_usage')->nullable(); // Maksimal penggunaan per voucher
            $table->integer('max_usage_per_member')->default(1); // Max usage per member
            $table->integer('stock')->nullable(); // Stok voucher (null = unlimited)
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
