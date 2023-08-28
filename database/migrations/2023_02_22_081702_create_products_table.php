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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('name');
            $table->unsignedInteger('visit')->default(0);
            $table->string('primary_image');
            $table->string('sku');
            $table->foreignId('childCategory_id')->nullable();
            $table->foreignId('brand_id');
            $table->foreignId('collection_id')->nullable();
            $table->text('description')->nullable();
            $table->string('property');
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('sell_count')->default(0);
            $table->unsignedInteger('delivery_amount')->default(32000);
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
        Schema::dropIfExists('products');
    }
};
