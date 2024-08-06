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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->enum("type",["installment","subscription"]);
            $table->bigInteger('count');
            $table->unsignedBigInteger('price');
            $table->date("due_date");
            $table->enum('status',['paid','unpaid','error'])->default("unpaid");
            $table->text("admin_description")->nullable();
            $table->unsignedBigInteger("loan_id")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
