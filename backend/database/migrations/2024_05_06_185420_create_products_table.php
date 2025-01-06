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

            //$table->foreignId("products_default_id")->constrained();
            $table->decimal("price")->nullable()->unsigned();
            $table->decimal("price_promo")->nullable()->unsigned();
            $table->foreignId("tennis_court_id")->constrained();
            $table->foreignId("user_id")->constrained();

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
