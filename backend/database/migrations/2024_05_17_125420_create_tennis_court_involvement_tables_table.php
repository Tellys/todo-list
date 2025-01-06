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
        Schema::create('tennis_court_involvement_tables', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("involvement")->nullable();
            $table->foreignId('icon_id')->nullable()->default(106)->constrained();  //106 = fas fa-check
            $table->string("description")->nullable();
            $table->foreignId("user_id")->constrained();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('tennis_court_involvements', function (Blueprint $table) {
            $table->bigInteger('tennis_court_involvement_table_id')->unsigned();
            $table->foreign('tennis_court_involvement_table_id', 'tcit_id')->references('id')->on('tennis_court_involvement_tables');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tennis_court_involvement_tables');
    }
};
