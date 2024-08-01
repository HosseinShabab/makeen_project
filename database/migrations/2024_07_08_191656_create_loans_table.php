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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("loan_number");
            $table->unsignedBigInteger("price");
            $table->text("admin_description")->nullable();
            $table->text("user_description")->nullable();
            $table->enum('type',["normal","necessary"]);
            $table->enum("admin_accept",["accepted","faild","pending"])->default("pending");
            $table->enum("guarantors_accept",["accepted","faild","pending"])->default("pending");
            $table->unsignedBigInteger("user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
