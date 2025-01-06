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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            
            //$table->string("status")->nullable(); // 'o controle passou a ser pelo softdelete'
            //$table->foreignId("tennis_court_calendar_id")->default(1)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId("tennis_court_calendar_id")->nullable()->constrained()->cascadeOnDelete();
            //$table->foreignId("product_id")->nullable()->default(1)->constrained();
            $table->foreignId("product_id")->nullable()->constrained();
            $table->string("product_name")->nullable();
            $table->decimal("qty")->unsigned();
            $table->decimal("price")->unsigned();
            $table->decimal("price_promo")->nullable()->unsigned();
            $table->decimal("discount")->unsigned()->nullable();
            $table->longText("discount_justification")->nullable();

            $table->foreignId("discount_policy_id")->nullable()->constrained();
            //$table->foreignId("discount_policy_id")->nullable()->default(1)->constrained();
            //$table->time("purchase_expiration_time")->default('00:15');
            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users');

            $table->foreignId("user_id")->constrained();

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
        Schema::dropIfExists('carts');
    }
};
