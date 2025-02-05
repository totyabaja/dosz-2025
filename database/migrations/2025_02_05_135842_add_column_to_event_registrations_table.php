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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->json('reg_form_response');
            $table->json('feedback_form_response');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreignUuid('event_reg_form_id')->nullable();
            $table->foreignUuid('event_feedback_form_id')->nullable();

            $table->foreign('event_reg_form_id')->references('id')->on('custom_forms')->onDelete('cascade');
            $table->foreign('event_feedback_form_id')->references('id')->on('custom_forms')->onDelete('cascade');
        });

        // TODO
        //Schema::dropIfExists('event_form_responses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign(['event_reg_form_id']);
            $table->dropForeign(['event_feedback_form_id']);
            $table->dropColumn('event_reg_form_id');
            $table->dropColumn('event_feedback_form_id');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('reg_form_response');
            $table->dropColumn('feedback_form_response');
        });
    }
};
