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
        Schema::table('scientific_departments', function (Blueprint $table) {
            $table->dropColumn('name_hu');
            $table->dropColumn('name_en');
            $table->json('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'scientific_departments',
            function (Blueprint $table) {
                $table->string('name_hu');
                $table->string('name_en');
                $table->dropColumn('name');
            }
        );
    }
};
