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
        Schema::create('loan_guarantor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guarantor_id');
            $table->string('guarantor_name');
            $table->unsignedBigInteger('loan_id');
            $table->enum("guarantor_accept", ["accepted", "faild", "pending"])->default("pending");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_guarantor');
    }
};
