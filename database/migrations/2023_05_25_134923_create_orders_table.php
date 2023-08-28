<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('address_id');
            $table->foreignId('discount_id')->nullable();
            $table->foreignId('gift_id')->nullable();
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('discounted_amount')->default(0);
            $table->unsignedInteger('paying_amount');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('payment_status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
