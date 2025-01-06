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
        Schema::create('tennis_court_description_tables', function (Blueprint $table) {
            $table->id();
            //$table->tinyInteger('master')->nullable()->default(null)->unsigned();
            $table->string('name')->unique();
            //$table->foreignId('icon_id')->constrained()->nullable()->default(106);  //106 = fas fa-check
            $table->string('unit')->nullable();
            $table->bigInteger('score')->nullable()->unsigned();
            //$table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId("users_level_id")->nullable()->default(4)->constrained();
        });

        Schema::table('config_cidades', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId("config_estados_id")->nullable()->constrained();
        });

        Schema::table('slugs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });

        Schema::table('config_sistems', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });

        Schema::table('user_level_roles', function (Blueprint $table) {
            $table->foreignId("users_level_id")->nullable()->constrained();
            $table->foreignId("user_id")->constrained()->nullable();
        });

        Schema::table('cnaes', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained()->nullable();
        });

        Schema::table('user_cnaes', function (Blueprint $table) {
            $table->foreignId("cnae_id")->constrained()->nullable();
            $table->foreignId("user_id")->constrained()->nullable();
            $table->foreignId("cliente_id")->nullable();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained()->nullable();
        });

        Schema::table('tennis_courts', function (Blueprint $table) {
            $table->foreignId("users_level_id")->nullable()->default(4)->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId("tennis_court_group_id")->nullable()->default(1)->constrained();
        });


        Schema::table('tennis_court_types', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
        });

        Schema::table('tennis_court_groups', function (Blueprint $table) {
            $table->foreignId("tennis_court_type_id")->constrained();
            $table->foreignId('user_id')->constrained();
        });

        Schema::table('tennis_court_descriptions', function (Blueprint $table) {
            $table->bigInteger('tennis_court_description_table_id')->unsigned();
            $table->foreign('tennis_court_description_table_id', 'tcdt_id')->references('id')->on('tennis_court_description_tables');

            //$table->foreignId("tennis_court_description_table_id")->nullable()->constrained();
            $table->foreignId("tennis_court_id")->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
        });

        Schema::table('tennis_court_description_tables', function (Blueprint $table) {
            $table->foreignId('icon_id')->constrained()->default(106)->nullable();  //106 = fas fa-check
            $table->foreignId('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tennis_court_description_tables');
    }
};
