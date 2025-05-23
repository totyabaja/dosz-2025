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
        Schema::create('scientific_department_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scientific_department_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('scientific_department_id')->references('id')->on('scientific_departments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientific_department_user');
    }
};
