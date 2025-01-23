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
        Schema::create('event_publication_abstracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_publication_id');
            $table->json('title');
            $table->json('abstract');
            $table->json('keywords');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_publication_id')->references('id')->on('event_publications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_publication_abstracts');
    }
};
