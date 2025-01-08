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
        Schema::create('user_cnaes', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable()->default('nome');
            $table->string("type")->nullable()->default('secundario');
            // $table->foreignId("cnae_id")->constrained()->nullable();
            // $table->foreignId("user_id")->constrained()->nullable();
            // $table->foreignId("cliente_id")->nullable();
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
        Schema::dropIfExists('user_cnaes');
    }
};
