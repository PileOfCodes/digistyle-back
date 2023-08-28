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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_name');
            $table->unsignedInteger('national_code')->nullable();
            $table->string('postal_code');
            $table->string('sex')->nullable();
            $table->string('resedence')->nullable();
            $table->unsignedInteger('mobile');
            $table->unsignedInteger('phone')->nullable();
            $table->unsignedInteger('city_code')->nullable();
            $table->text('address');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->tinyInteger('is_foreigner')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('addresses');
    }
};
