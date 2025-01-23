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
        Schema::create('custom_form_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('custom_form_id');
            $table->string('type'); // text, select, number, checkbox, radio
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('tooltip')->nullable();
            $table->timestamps();

            $table->foreign('custom_form_id')->references('id')->on('custom_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_form_elements');
    }
};
