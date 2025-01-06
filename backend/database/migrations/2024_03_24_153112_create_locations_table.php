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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->integer("city_code")->nullable();
            $table->string("city")->nullable();
            $table->string("neighborhood")->nullable();
            $table->string("state_code")->nullable();
            $table->string("state")->nullable();
            $table->string("state_short")->nullable();
            $table->string("country")->nullable()->default("Brasil");
            $table->string("country_short")->nullable();
            $table->string("country_code")->nullable();
            $table->decimal("lat", 11,8)->nullable();
            $table->decimal("lng", 11,8)->nullable();
            //$table->point("geo_point")->nullable();
            $table->string("timezone")->nullable()->default("America/Sao_Paulo");
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
        Schema::dropIfExists('locations');
    }
};
