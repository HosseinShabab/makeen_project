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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum("type",["subscription",'installment']);
            $table->unsignedBigInteger("membership_price");
            $table->unsignedBigInteger("installment_number");
            $table->bigInteger("transfer_price");
            $table->date("payment_date");
            $table->enum("status",["paid","unpaid"]);
            $table->enum("accept_status",["accepted",'update_required',"failed"]);
            $table->text("description");
            $table->unsignedBigInteger("admins_card");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
