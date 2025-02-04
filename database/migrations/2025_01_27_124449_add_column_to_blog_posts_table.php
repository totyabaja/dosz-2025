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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('scientific_department_id')
                ->nullable();
            $table->softDeletes();

            $table->foreign('scientific_department_id')->references('id')->on('scientific_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['scientific_department_id']);
            $table->dropColumn('scientific_department_id');
            $table->dropSoftDeletes();
        });
    }
};
