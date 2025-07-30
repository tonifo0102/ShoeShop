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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('shipper_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('discount_name')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('discount_percent')->nullable();
            $table->enum('pay', ['unpaid', 'paid'])->default('unpaid');
            $table->string('payment_method')->default('direct');
            $table->string('shipping_address')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->enum('status', ['cart', 'wait', 'shipping', 'complete', 'cancel'])->default('cart');
            $table->timestamps();
            
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('shipper_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}; 