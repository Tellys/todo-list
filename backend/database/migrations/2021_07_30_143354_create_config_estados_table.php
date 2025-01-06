<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_estados', function (Blueprint $table) {
            $table->id();
            $table->string('short_name')->unique();
            $table->string('name')->unique();
            //$table->string('user_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('config_cidades', function (Blueprint $table) {
            //$table->foreignId("config_estados_id")->nullable()->constrained();
        });

        /* Schema::table('users', function (Blueprint $table) {
            $table->foreignId("config_estados_id")->nullable()->constrained()->onDelete("cascade");
            $table->foreignId("config_cidades_id")->nullable()->constrained()->onDelete("cascade");
        }); */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_estados');
    }
}
