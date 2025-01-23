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
        Schema::create('scientific_subfield_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreignId('scientific_subfield_id');
            $table->json('keywords')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('scientific_subfield_id')->references('id')->on('scientific_subfields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scientific_subfield_user');
    }
};
