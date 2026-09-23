<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->string('text')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->string('relation_with')->nullable();
            $table->integer('position')->default(1000);
            $table->bigInteger('menu_item_id')->nullable();
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
        Schema::dropIfExists('menu_items');
    }
}
