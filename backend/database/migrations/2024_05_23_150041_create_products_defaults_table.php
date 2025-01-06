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
        Schema::create('products_defaults', function (Blueprint $table) {
            $table->id();
            
            $table->string("name")->nullable()->unique();
            $table->string("description")->nullable();
            $table->foreignId("product_department_id")->constrained();
            $table->string("unit")->nullable();
            $table->string("image")->nullable();
            $table->foreignId("user_id")->constrained();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId("products_default_id")->constrained();
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_defaults');
    }
};
