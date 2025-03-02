<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->text('description')->nullable(); 
            $table->decimal('price', 8, 2); 
            $table->enum('category', ['antipasto', 'pizza', 'primo', 'secondo', 'contorno', 'dolce', 'bevande']);
            $table->boolean('is_available')->default(true);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('menus');
    }
}