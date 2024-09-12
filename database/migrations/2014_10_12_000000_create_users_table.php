<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string("password");
            $table->string('phone_number')->unique();
            $table->string('emergency_number')->nullable();
            $table->string('home_number')->nullable();
            $table->string('national_code')->unique();
            $table->string('card_number')->nullable();
            $table->string('sheba_number')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('debt')->default(0);
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
