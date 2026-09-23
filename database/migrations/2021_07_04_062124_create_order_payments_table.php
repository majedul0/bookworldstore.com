<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('status', 55)->default('Active'); // Pending, Active, Rejected, Refunded
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->double('amount');
            $table->string('gateway_order_id')->nullable();
            $table->string('txn_number')->nullable();
            $table->string('card_number')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
