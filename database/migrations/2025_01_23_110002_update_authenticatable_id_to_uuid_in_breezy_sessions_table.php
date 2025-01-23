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
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->dropMorphs('authenticatable');
        });
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->uuidMorphs('authenticatable'); // Létrehozza UUID-alapú morph oszlopokat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->dropMorphs('authenticatable');
        });
        Schema::table('breezy_sessions', function (Blueprint $table) {
            $table->uuidMorphs('authenticatable');
        });
    }
};
