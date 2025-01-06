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
        Schema::create('tennis_courts', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("name")->nullable();
            $table->string("name_corporate")->nullable(); //razão social
            $table->string("password")->nullable();
            $table->dateTime("birthday")->nullable();
            $table->string("cpf")->nullable()->unique();
            $table->string("slug")->nullable()->unique();
            $table->string("email")->nullable()->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("country")->nullable();  //Minas Gerais
            $table->string("country_code")->nullable(); //MG
            $table->string("state")->nullable();  //Minas Gerais
            $table->string("state_code")->nullable(); //MG
            $table->string("city")->nullable();
            $table->integer("zip_code")->unsigned()->default(00000000)->length(8)->nullable();
            $table->string("address")->nullable();
            $table->string("address_neighborhood")->nullable();
            $table->integer("address_num")->nullable()->unsigned();
            $table->string("address_complement")->nullable();
            $table->bigInteger("phone")->nullable()->unsigned();
            $table->bigInteger("cell_phone")->nullable()->unsigned();
            $table->timestamp("cell_phone_verified_at")->nullable();
            $table->string("type_login")->nullable(); //tipo de login via rede social
            $table->string("web_site")->unique()->nullable();
            $table->decimal("lat", 11,8)->nullable();
            $table->decimal("lng", 11,8)->nullable();
            //$table->point("geo_point")->nullable();
            $table->string("description")->nullable();
            $table->string("registration_phase")->nullable()->defaule('dashboardTennisCourtCreate');

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
        Schema::dropIfExists('tennis_courts');
    }
};
