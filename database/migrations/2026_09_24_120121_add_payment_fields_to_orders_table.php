<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_sender_number')->nullable()->after('payment_transaction_id');
            $table->decimal('payment_claimed_amount', 10, 2)->nullable()->after('payment_sender_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_sender_number', 'payment_claimed_amount']);
        });
    }
};
