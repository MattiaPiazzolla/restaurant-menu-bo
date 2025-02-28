<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_statuses');
    }
};