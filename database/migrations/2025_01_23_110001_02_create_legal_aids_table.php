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
        Schema::create('legal_aids', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('university_id')->nullable();
            $table->foreignId('doctoral_school_id')->nullable();
            $table->text('question')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
            $table->foreign('doctoral_school_id')->references('id')->on('doctoral_schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_aids');
    }
};
