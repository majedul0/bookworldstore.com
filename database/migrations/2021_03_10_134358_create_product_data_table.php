<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_data', function (Blueprint $table) {
            $table->id();
            $table->string('type', 55)->default('Simple'); // Simple, Variable, Bundle
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('attribute_item_ids')->nullable();
            $table->double('regular_price')->nullable();
            $table->double('sale_price');
            $table->double('cost')->nullable();
            $table->double('discount_percent')->nullable();
            $table->double('promotion_price')->nullable();
            $table->timestamp('promotion_start_date')->nullable();
            $table->timestamp('promotion_end_date')->nullable();
            $table->string('sku_code')->nullable();
            $table->double('shipping_weight')->nullable(); // Gram
            $table->double('shipping_width')->nullable();
            $table->double('shipping_height')->nullable();
            $table->double('shipping_length')->nullable();
            $table->integer('rack_number')->nullable();
            $table->string('unit', 55)->nullable();
            $table->double('unit_amount')->nullable();
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->double('stock')->default(0);
            $table->string('stock_managed_by')->default('Self'); // Self, Other
            $table->bigInteger('stock_product_data_id')->nullable();
            $table->boolean('stock_conversion_fact')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->double('tax_amount')->default(0);
            $table->string('tax_type')->nullable(); // Fixed, Percentage
            $table->string('tax_method')->nullable(); // Exclusive, Inclusive
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
        Schema::dropIfExists('product_data');
    }
}
