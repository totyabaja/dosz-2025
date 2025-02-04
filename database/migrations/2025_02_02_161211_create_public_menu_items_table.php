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
        Schema::create('public_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('custom_id')->unique()->nullable();
            $table->foreignUuid('parent_id')->nullable(); // parent
            $table->json('label');              // menü felirat
            $table->string('slug')->nullable();     // belső link
            $table->string('external_url')->nullable(); // külső link
            $table->string('target')->nullable();   // pl. _blank
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_menus');
    }
};
