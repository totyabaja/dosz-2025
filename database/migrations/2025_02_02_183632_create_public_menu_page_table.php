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
        Schema::create('public_menu_page', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('public_menu_id');
            $table->foreignId('page_id');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('public_menu_id')->references('id')->on('public_menus')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_menu_page');
    }
};
