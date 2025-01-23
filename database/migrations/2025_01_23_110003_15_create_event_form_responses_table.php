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
        Schema::create('event_form_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_registration_id');
            $table->foreignUuid('custom_form_id');
            $table->json('responses');
            $table->timestamps();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations')->onDelete('cascade');
            $table->foreign('custom_form_id')->references('id')->on('custom_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_form_responses');
    }
};
