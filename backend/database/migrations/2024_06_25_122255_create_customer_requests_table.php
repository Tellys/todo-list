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
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->id();
            
            $table->string("status")->nullable()->default("shopping");
            $table->decimal("price")->default(0.00)->unsigned();
            $table->decimal("price_promo")->nullable()->unsigned();
            $table->decimal("discount")->unsigned()->nullable();
            $table->longText("discount_justification")->nullable();
            //$table->foreignId("payment_method_id")->nullable()->constrained();
            //$table->longText("payment_log")->nullable();

            $table->foreignId("discount_policy_id")->default(1)->nullable()->constrained();

            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users');

            $table->foreignId("user_id")->constrained();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId("customer_request_id")->nullable()->constrained();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_requests');
    }
};
