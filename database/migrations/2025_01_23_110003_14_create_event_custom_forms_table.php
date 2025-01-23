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
        Schema::create('event_custom_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('event_id');
            $table->foreignUuid('custom_form_id');
            $table->string('type')->default('reg');
            $table->timestamps();

            $table->foreign('custom_form_id')->references('id')->on('custom_forms')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_custom_forms');
    }
};
