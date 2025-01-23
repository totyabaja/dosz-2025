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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('scientific_state_id')->nullable()->after('fokozateve');
            //$table->foreignId('scientific_subfield_id')->nullable()->after('scientific_state_id');

            $table->foreign('scientific_state_id')->references('id')->on('scientific_states')->onDelete('cascade');
            //$table->foreign('scientific_subfield_id')->references('id')->on('scientific_subfields')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['scientific_state_id']);
            //$table->dropForeign(['scientific_subfield_id']);

            $table->dropColumn('scientific_state_id');
            //$table->dropColumn('scientific_subfield_id');
        });
    }
};
