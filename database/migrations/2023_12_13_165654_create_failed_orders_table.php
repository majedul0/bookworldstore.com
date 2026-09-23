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
        Schema::create('failed_orders', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_mobile_number')->nullable();
            $table->string('shipping_email')->nullable();
            $table->double('shipping_charge')->default(0);
            $table->string('last_event');
            $table->longText('others_data')->nullable(); // Json Data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_orders');
    }
};
