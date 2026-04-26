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
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->string('payment_status', 20)->default('unpaid')->after('status');
            $table->string('payment_method', 30)->nullable()->after('payment_status');
            $table->string('midtrans_order_id', 100)->nullable()->after('payment_method');
            $table->string('midtrans_transaction_id', 100)->nullable()->after('midtrans_order_id');
            $table->text('payment_redirect_url')->nullable()->after('midtrans_transaction_id');
            $table->timestamp('paid_at')->nullable()->after('payment_redirect_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'payment_redirect_url',
                'paid_at',
            ]);
        });
    }
};