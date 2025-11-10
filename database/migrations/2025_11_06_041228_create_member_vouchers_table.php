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
        Schema::create('member_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade'); // Member yang punya
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade'); // Voucher yang diredeem
            $table->string('voucher_code'); // Kode unik voucher member
            $table->integer('points_used'); // Poin yang digunakan saat redeem
            $table->date('redeemed_at'); // Tanggal redeem
            $table->date('expires_at'); // Tanggal kadaluarsa
            $table->enum('status', ['available', 'used', 'expired'])->default('available'); // Status voucher
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null'); // Transaksi yang pakai voucher
            $table->timestamp('used_at')->nullable(); // Kapan dipakai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_vouchers');
    }
};
