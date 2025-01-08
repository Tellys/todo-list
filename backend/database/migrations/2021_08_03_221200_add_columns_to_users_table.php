<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('identidade_civil')->nullable()->unsigned();
            $table->string('identidade_civil_emissor')->nullable();
            $table->string('identidade_civil_emissor_uf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('identidade_civil', 'identidade_civil_emissor','identidade_civil_emissor_uf','cpf');
        });
    }
}
