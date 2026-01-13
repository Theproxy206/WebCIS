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
        Schema::create('Materials', function (Blueprint $table) {
            $table->
            $table->unsignedInteger('mat_serial', true)->primary();
            $table->string('mat_title', 80);
            $table->timestampTz('mat_publication_date');
            $table->string('mat_code', 14);
            $table->string('mat_description', 300)->nullable();
            $table->string('fk_users_materials', 36);
            $table->foreign('fk_users_materials')->references('user_id')->on('Users');
        });

        Schema::create('Extensions', function (Blueprint $table){
            $table->unsignedInteger('ext_serial', true)->primary();
            $table->string('ext_name', 15);
            $table->string('ext_mime_type', 100);
            $table->unsignedTinyInteger('ext_category');
            $table->boolean('ext_active');
            $table->unsignedSmallInteger('ext_max_mb');
            $table->timestampsTz();
        });

        Schema::create('Files', function (Blueprint $table) {
            $table->unsignedInteger('fil_serial', true)->primary();
            $table->string('fil_name', 100);
            $table->string('fil_path', 255);
            $table->timestampsTz();
            $table->unsignedInteger('fk_extension_type');
            $table->unsignedInteger('fk_material_file');
            $table->foreign('fk_extension_type')->references('ext_serial')->on('Extensions');
            $table->foreign('fk_material_file')->references('mat_serial')->on('Materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Materials');
        Schema::dropIfExists('Extensions');
        Schema::dropIfExists('Files');
    }
};
