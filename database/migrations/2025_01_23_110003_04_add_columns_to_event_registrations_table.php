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
            $table->string('notification_email');
            $table->foreignId('doctoral_school_id')->nullable();
            $table->foreignId('scientific_department_id')->nullable();

            $table->foreign('doctoral_school_id')->references('id')->on('doctoral_schools')->onDelete('cascade');
            $table->foreign('scientific_department_id')->references('id')->on('scientific_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('notification_email');

            $table->dropForeign(['doctoral_school_id']);
            $table->dropForeign(['scientific_department_id']);

            $table->dropColumn('doctoral_school_id');
            $table->dropColumn('scientific_department_id');
        });
    }
};
