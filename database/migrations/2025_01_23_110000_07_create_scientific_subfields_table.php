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
        Schema::create('scientific_subfields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('scientific_field_id');
            $table->timestamps();

            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientific_subfields');
    }
};
