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
        Schema::table('transactions', function (Blueprint $table) {
            // Audit trail untuk akuntabilitas dan mencegah fraud
            $table->string('cashier_name', 100)->after('cashier_id')->nullable()->comment('Nama kasir saat transaksi untuk audit');
            $table->ipAddress('ip_address')->after('transaction_date')->nullable()->comment('IP address kasir saat transaksi');
            $table->text('user_agent')->after('ip_address')->nullable()->comment('Browser/device info');
            $table->text('transaction_notes')->after('user_agent')->nullable()->comment('Catatan khusus transaksi');
            $table->timestamp('verified_at')->after('transaction_notes')->nullable()->comment('Waktu verifikasi supervisor jika ada');
            $table->foreignId('verified_by')->after('verified_at')->nullable()->constrained('users')->onDelete('set null')->comment('Supervisor yang verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'cashier_name',
                'ip_address', 
                'user_agent',
                'transaction_notes',
                'verified_at',
                'verified_by'
            ]);
        });
    }
};
