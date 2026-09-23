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
        Schema::create('user_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no_1')->nullable();
            $table->string('invoice_no_2')->nullable();
            $table->text('description')->nullable();
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
            $table->double('current_balance')->default(0);
            $table->double('previous_balance')->default(0);
            $table->double('total_debit')->default(0);
            $table->double('total_credit')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ledgers');
    }
};
