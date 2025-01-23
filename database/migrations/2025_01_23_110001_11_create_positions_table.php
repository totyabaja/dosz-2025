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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_subtype_id')->nullable();
            $table->string('notes', 200)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignUuid('user_id');
            $table->string('email', 200)->nullable();
            $table->foreignId('scientific_department_id')->nullable();
            $table->text('areas');
            $table->timestamps();

            $table->foreign('position_subtype_id')->references('id')->on('position_subtypes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('scientific_department_id')->references('id')->on('scientific_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
