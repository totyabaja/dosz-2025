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
        Schema::create('event_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('event_registration_id');
            $table->integer('publication_order');
            $table->timestamps();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_publications');
    }
};
