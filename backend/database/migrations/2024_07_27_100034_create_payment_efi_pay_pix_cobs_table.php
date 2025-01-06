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
        Schema::create('payment_efi_pay_pix_cobs', function (Blueprint $table) {
            $table->id();

            $table->string("txid")->unique()->nullable();
            $table->string("status")->nullable();
            $table->longText("image")->nullable();
            $table->longText("qrcode")->nullable();
            $table->integer("http_code")->nullable()->unsigned();
            $table->longText("json")->nullable();
            $table->foreignId("customer_request_id")->nullable()->constrained();
            $table->foreignId("user_id")->constrained();

            $table->string("chave")->nullable();
            $table->decimal("valor")->nullable()->unsigned()->default(0);
            $table->timestamp("horario")->nullable();
            $table->longText("gn_extras")->nullable();
            $table->string("devolucoes_status")->nullable();
            $table->decimal("devolucoes_valor")->nullable()->unsigned();
            $table->longText("devolucoes")->nullable();

            $table->string("end_to_end_id")->unique()->nullable();
            $table->string("solicitacao_pagador")->nullable();
            
            $table->timestamp('expires_in')->nullable();
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
        Schema::dropIfExists('payment_efi_pay_pix_cobs');
    }
};
