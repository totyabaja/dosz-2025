<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('blog_posts')->truncate();

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->renameColumn('short_content', 'short_description');
            $table->renameColumn('title', 'name');
            $table->renameColumn('content', 'description');
        });
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('short_description')->change();
            $table->json('name')->change();
            $table->json('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('short_description', 255)->change();
            $table->renameColumn('short_description', 'short_content');

            $table->string('description', 255)->change();
            $table->renameColumn('description', 'content');

            $table->string('name', 255)->change();
            $table->renameColumn('name', 'title');
        });
    }
};
