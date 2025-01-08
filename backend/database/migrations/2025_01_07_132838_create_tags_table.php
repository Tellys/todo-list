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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable()->unique();
            $table->string("color")->nullable()->unique();
            $table->foreignId("user_id")->constrained();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId("tag_id")->nullable()->constrained();
        });

        //
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId("users_level_id")->nullable()->default(4)->constrained();
        });

        Schema::table('config_sistems', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });

        Schema::table('user_level_roles', function (Blueprint $table) {
            $table->foreignId("users_level_id")->nullable()->constrained();
            $table->foreignId("user_id")->constrained()->nullable();
        });

        Schema::table('cnaes', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained()->nullable();
        });

        Schema::table('user_cnaes', function (Blueprint $table) {
            $table->foreignId("cnae_id")->constrained()->nullable();
            $table->foreignId("user_id")->constrained()->nullable();
            $table->foreignId("cliente_id")->nullable();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained()->nullable();
        });       

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
