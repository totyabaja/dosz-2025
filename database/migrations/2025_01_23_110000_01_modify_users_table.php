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
            $table->string('email_intezmenyi', 255)->nullable()->after('remember_token');
            $table->string('mobil', 50)->nullable()->after('email_intezmenyi');
            $table->string('disszertacio', 255)->nullable()->after('mobil');
            $table->string('kutatohely', 255)->nullable()->after('disszertacio');
            $table->boolean('multi_tudomanyag')->default(false)->after('kutatohely');
            $table->boolean('tudfokozat')->default(false)->after('multi_tudomanyag');
            $table->integer('fokozateve')->nullable()->after('tudfokozat');
            $table->dateTime('adatvedelmit_elfogadta')->nullable()->after('fokozateve');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_intezmenyi');
            $table->dropColumn('mobil');
            $table->dropColumn('disszertacio');
            $table->dropColumn('kutatohely');
            $table->dropColumn('multi_tudomanyag');
            $table->dropColumn('tudfokozat');
            $table->dropColumn('fokozateve');
            $table->dropColumn('adatvedelmit_elfogadta');
        });
    }
};
