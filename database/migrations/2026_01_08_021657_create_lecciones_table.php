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
        Schema::create('courses', function (Blueprint $table) {
            $table->string('cou_token', 12)->primary();
            $table->string('cou_title', 200);
            $table->string('cou_short_title', 80);
            $table->string('cou_description', 300)->nullable();
            $table->timestampsTz();
            $table->string('cou_code', 14)->unique();
            $table->string('cou_content', 255);
            $table->string('cou_path_icon', 255)->nullable();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->unsignedInteger('les_serial', true)->primary();
            $table->string('les_title', 200);
            $table->string('les_short_title', 60);
            $table->timestampsTz();
            $table->string('fk_lessons_courses', 12);
            $table->unsignedInteger('fk_lessons_lessons');
            $table->foreign('fk_lessons_courses')->references('cou_token')->on('courses');
            $table->foreign('fk_lessons_lessons')->references('les_serial')->on('lessons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('lessons');
    }
};
