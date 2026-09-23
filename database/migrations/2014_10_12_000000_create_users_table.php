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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1); // 1- Active, 2- Pending, 0- Suspended
            $table->boolean('admin_read')->default(2);
            $table->string('type', 55)->default('customer'); // customer, admin, employee, user, supplier
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('designation')->nullable();
            $table->string('username', 191)->nullable()->unique();
            $table->string('mobile_number', 191);
            $table->string('email', 191)->nullable()->unique();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('profile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->longText('address')->nullable(); // Json data for multiple address
            $table->double('balance')->default(0);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
