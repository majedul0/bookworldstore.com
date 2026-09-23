<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->string('code');
            $table->text('description')->nullable();
            $table->string('discount_type', 55); // percent, fixed
            $table->integer('amount')->default(0);
            $table->timestamp('expiry_date')->nullable();
            $table->integer('minimum_spend')->nullable();
            $table->integer('maximum_spend')->nullable();
            $table->integer('maximum_use')->nullable();
            $table->integer('total_use')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('visibility')->default(1); // 1- Visible, 2- Hidden
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
        Schema::dropIfExists('coupons');
    }
}
