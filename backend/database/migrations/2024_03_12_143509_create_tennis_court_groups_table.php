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
        Schema::create('tennis_court_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            //$table->string("slug")->nullable()->unique();
            $table->string("description")->nullable();
            $table->string("tennis_court_description_table_id")->nullable();
            //$table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('tennis_courts', function (Blueprint $table) {
            //$table->foreignId("tennis_courts_id")->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tennis_court_groups');
    }
};
