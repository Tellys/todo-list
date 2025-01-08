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

        Schema::create('user_level_roles', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable()->default('nome');
            $table->string("action")->default('none');
            $table->string("param")->nullable()->default(0);
            // $table->foreignId("users_level_id")->nullable()->constrained();
            // $table->foreignId("user_id")->constrained()->nullable()->onDelete("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level_roles');
    }
};
