<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable()->unique();
            $table->string("body")->nullable();
            $table->string("level")->nullable()->default("info");
            $table->timestamp("time_start");
            $table->string("status")->nullable()->default("default");
            $table->string("check")->nullable();
            $table->tinyInteger("views")->nullable()->default(0)->unsigned();
            $table->foreignId("user_id")->constrained();
            $table->timestamp("expires_at")->nullable();
            $table->integer("expiration_notices_sent")->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
