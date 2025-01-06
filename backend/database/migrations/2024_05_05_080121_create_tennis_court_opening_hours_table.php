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
        Schema::create('tennis_court_opening_hours', function (Blueprint $table) {
            $table->id();
            $table->string("day");
            $table->string("hour_start");
            $table->string("hour_end");
            $table->decimal("price")->nullable()->unsigned();
            $table->decimal("price_promo")->nullable()->unsigned();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("tennis_court_id")->constrained();
            $table->time("purchase_expiration_time")->default(env('CART_EXPIRES_AT', '00:15:00'));

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('tennis_court_calendars', function (Blueprint $table) {
            $table->foreignId("tennis_court_opening_hour_id")->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tennis_court_opening_hours');
    }
};
