<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_data_id');
            $table->string('title')->nullable();
            $table->text('attribute_item_ids')->nullable();
            $table->integer('quantity');
            $table->integer('return_quantity')->default(0);
            $table->double('sale_price')->default(0);
            $table->double('shipping_weight')->default(0); // Gram
            $table->string('shipping_length')->nullable(); // Height, Width, Length
            $table->integer('tax_amount')->default(0);
            $table->string('tax_type')->nullable(); // Fixed, Percentage
            $table->string('tax_method')->nullable(); // Exclusive, Inclusive
            $table->text('simple_attributes')->nullable(); // Json Data
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
        Schema::dropIfExists('order_products');
    }
}
