<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string("api_id")->unique();
            $table->foreignId("set_id");
            $table->string("name");
            $table->integer("number");
            $table->string("version");
            $table->string("cardIdentifier");
            $table->text("description");
            $table->string("image");
            $table->string("thumbnail");
            $table->string("rarity");
            $table->string("story");

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
