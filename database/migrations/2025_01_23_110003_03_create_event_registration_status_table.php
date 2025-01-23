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
        Schema::create('event_registration_status', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('event_registration_id');
            $table->foreignId('event_status_id');
            $table->json('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('event_registration_id')->references('id')->on('event_registrations')->onDelete('cascade');
            $table->foreign('event_status_id')->references('id')->on('event_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registration_status');
    }
};
