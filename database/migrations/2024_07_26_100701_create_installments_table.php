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
            $table->enum('status',['paid','unpaid','error'])->default("upaid");
            $table->enum("admin_accept",["accepted",'faid'])->nullable();
            $table->text("admin_description")->nullable();
            $table->unsignedBigInteger('paid_price')->nullable();
            $table->text("user_description")->nullable();
            $table->unsignedBigInteger("loan_id")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string('user_name');
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
