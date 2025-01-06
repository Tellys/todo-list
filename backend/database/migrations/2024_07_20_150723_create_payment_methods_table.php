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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable()->unique();
            $table->string("type")->nullable();
            $table->string("status")->nullable()->default("operational");
            $table->string("payment_method_controller")->nullable();
            $table->string("financial_institution")->nullable();
            $table->decimal("rate")->nullable()->unsigned(); //taxa
            $table->timestamp("deadline_for_receipt"); // prazo para recebimento da instituição financeira
            $table->string("description")->nullable();
            $table->foreignId("user_id")->constrained();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('customer_requests', function (Blueprint $table) {
            $table->foreignId("payment_method_id")->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
