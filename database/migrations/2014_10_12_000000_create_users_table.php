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
            $table->string('user_name')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('phone_number')->unique();
            $table->unsignedBigInteger('emergency_number')->unique();
            $table->unsignedBigInteger('home_number');
            $table->unsignedBigInteger('national_code')->unique();
            $table->unsignedBigInteger('card_number');
            $table->unsignedBigInteger('sheba_number');
            $table->string('address');
            $table->string('password');
            $table->rememberToken();
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
