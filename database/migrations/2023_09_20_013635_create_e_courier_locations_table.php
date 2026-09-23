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
        Schema::create('e_courier_locations', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');
            $table->string('city_value');
            $table->string('thana_name');
            $table->string('thana_value');
            $table->string('zip_name');
            $table->string('zip_value');
            $table->string('area_name');
            $table->string('area_value');
            $table->string('all_name', 555);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_courier_locations');
    }
};
