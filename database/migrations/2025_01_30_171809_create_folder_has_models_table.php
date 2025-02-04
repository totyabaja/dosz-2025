<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('folder_has_models');
        Schema::create('folder_has_models', function (Blueprint $table) {
            $table->id();

            //Morph
            $table->string('model_type');
            $table->uuid('model_id');

            //Folder
            $table->foreignUuid('folder_id');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folder_has_models');
    }
};
