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
        Schema::table('scientific_department_user', function (Blueprint $table) {
            $table->boolean('accepted')->nullable();
            $table->dateTime('request_datetime')->useCurrent();
            $table->dateTime('acceptance_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scientific_department_user', function (Blueprint $table) {
            $table->dropColumn('accepted');
            $table->dropColumn('request_datetime');
            $table->dropColumn('acceptance_datetime');
        });
    }
};
