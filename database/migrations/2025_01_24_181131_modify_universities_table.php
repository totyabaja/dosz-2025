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
        Schema::table('universities', function (Blueprint $table) {
            $table->dropColumn('full_name_en');
            $table->dropColumn(mb_ucfirst(__('reg.fieldset.full_name')));
            $table->json(mb_ucfirst(__('reg.fieldset.full_name')));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->string('full_name_en');
            $table->string(mb_ucfirst(__('reg.fieldset.full_name')));
            $table->dropColumn(mb_ucfirst(__('reg.fieldset.full_name')));
        });
    }
};
