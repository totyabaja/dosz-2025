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
        Schema::create('position_subtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_type_id');
            $table->json('name');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('position_type_id')->references('id')->on('position_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_subtypes');
    }
};
