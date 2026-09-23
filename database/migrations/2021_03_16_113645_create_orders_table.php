<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Statuses
            $table->string('status', 55)->default('Pending'); // Pending, Delivered, Completed, In Courier, Canceled, Returned, Confirmed, Partial, Hold
            $table->boolean('admin_read')->default(2);
            $table->string('payment_status', 55)->default('Pending'); // Pending, Due, Partial, Paid, Refunded

            // Customer information
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('street')->nullable();
            $table->string('apartment')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();

            // Customer Shipping Information
            $table->string('shipping_full_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_mobile_number')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_apartment')->nullable();
            $table->string('shipping_post_code')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();
            $table->unsignedBigInteger('shipping_state_id')->nullable();

            // Shipping
            $table->double('shipping_charge')->default(0);
            $table->double('hidden_shipping_charge')->nullable();
            $table->string('shipping_method')->nullable();
            $table->double('shipping_weight')->default(0); // Gram
            $table->string('shipping_length')->nullable(); // Height, Width, Length
            $table->string('shipping_invoice')->nullable();
            $table->bigInteger('shipping_id')->nullable();
            $table->bigInteger('shipping_order_id')->nullable();

            // Charges
            $table->double('product_total')->default(0);
            $table->double('tax')->default(0); // Percent amount
            $table->double('tax_amount')->default(0); // Calculated amount
            $table->double('other_cost')->default(0);
            $table->double('discount')->default(0); // Percent amount
            $table->double('discount_amount')->default(0); // Calculated amount

            // Payment
            $table->double('paid_amount')->default(0);
            $table->string('payment_method', 55)->nullable();
            $table->string('payment_transaction_id')->nullable();

            // Refund
            $table->double('refund_shipping_amount')->default(0);
            $table->double('refund_other_charge')->default(0);
            $table->double('refund_product_total')->default(0);
            $table->double('refund_tax_amount')->default(0);
            $table->double('refund_total_amount')->default(0);

            // Coupon
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();

            // Notes
            $table->text('note')->nullable();
            $table->text('staff_note')->nullable();

            // Order Source
            $table->string('source')->nullable();

            $table->string('cancel_return_reason')->nullable();

            $table->boolean('order_tracked')->default(0);

            // Courier Data
            $table->string('courier')->nullable();
            $table->string('courier_invoice')->nullable();
            $table->string('courier_status')->nullable();
            $table->string('courier_payment_status')->nullable();
            $table->timestamp('courier_payment_at')->nullable();
            $table->timestamp('courier_submitted_at')->nullable(); // Courier Submitted Time or In Courier Status Time

            $table->string('reference_no')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
