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
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('event_start_date')->nullable()->change();
            $table->dateTime('event_end_date')->nullable()->change();
            $table->dateTime('event_registration_start_datetime')->nullable()->change();
            $table->dateTime('event_registration_end_datetime')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('event_start_date')->nullable(false)->change();
            $table->dateTime('event_end_date')->nullable(false)->change();
            $table->dateTime('event_registration_start_datetime')->nullable(false)->change();
            $table->dateTime('event_registration_end_datetime')->nullable(false)->change();
        });
    }
};
