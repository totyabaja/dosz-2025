<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            //$table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();

            //$table->string('name')->nullable();
            $table->string('slug')->nullable();

            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            //$table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();

            //$table->string('name')->nullable();
            $table->dropColumn('slug');

            //$table->timestamps();
        });
    }
};
