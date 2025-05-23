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
        Schema::create('doctoral_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id');
            $table->string(mb_ucfirst(__('reg.fieldset.full_name')));
            $table->string('short_name', 20);
            $table->string('url');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctoral_schools');
    }
};
