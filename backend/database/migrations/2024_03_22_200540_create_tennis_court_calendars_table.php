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
        Schema::create('tennis_court_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tennis_court_id")->constrained();
            $table->dateTime("time_start");
            $table->dateTime("time_end");
            $table->string("status")->default('in_cart');
            $table->foreignId("user_id")->constrained();
            
            $table->integer('expiration_notices_sent')->nullable();
            $table->timestamp('expires_at')->nullable();
            
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
        Schema::dropIfExists('tennis_court_calendars');
    }
};
