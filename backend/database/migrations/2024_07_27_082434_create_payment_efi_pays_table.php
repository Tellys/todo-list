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
        /* Schema::create('payment_efi_pays', function (Blueprint $table) {
            $table->id();

            $table->integer("http_code")->unsigned();
            $table->longText("json")->nullable();
            $table->foreignId("customer_request_id")->nullable()->constrained();
            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreignId("user_id")->constrained();

            $table->softDeletes();
            $table->timestamps();
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_efi_pays');
    }
};
