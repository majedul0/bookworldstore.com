<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->string('title');
            $table->string("slug", 300);
            $table->string('type', 55)->default('Simple'); // Simple, Variable, Bundle
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('clearance_sale')->default(0);
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_tags')->nullable();
            $table->integer('position')->default(1000);
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->double('stock')->default(0);
            $table->bigInteger('stock_alert_quantity')->default(0);
            $table->bigInteger('stock_pre_alert_quantity')->default(0);
            $table->string('custom_label')->nullable();
            $table->bigInteger('view')->default(0);
            $table->double('average_rating')->default(0); // Out of 5
            $table->integer('total_review')->default(0);
            $table->date('expire_date')->nullable();
            $table->double('sale_price')->default(0); // Selling Price
            $table->double('regular_price')->default(0); // Old Price
            $table->double('fist_price')->nullable();
            $table->double('last_price')->nullable();
            $table->text('attribute_items_id')->nullable(); // Json Data
            $table->text('youtube_video_embed')->nullable();
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
        Schema::dropIfExists('products');
    }
}
