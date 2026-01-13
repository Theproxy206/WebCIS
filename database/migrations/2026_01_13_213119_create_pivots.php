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
        Schema::create('users_medals', function (Blueprint $table) {
            $table->string('fk_users', 36);
            $table->unsignedInteger('fk_medals');

            $table->timestampTz('obtained_at');

            $table->primary(['fk_users', 'fk_medals']);

            $table->foreign('fk_users')->references('user_id')->on('users');
            $table->foreign('fk_medals')->references('med_serial')->on('medals');
        });

        Schema::create('courses_users', function (Blueprint $table) {
            $table->string('fk_courses', 12);
            $table->string('fk_users', 36);

            $table->char('type', 3);

            $table->primary(['fk_courses', 'fk_users']);

            $table->foreign('fk_courses')->references('cou_token')->on('courses');
            $table->foreign('fk_users')->references('user_id')->on('users');
        });

        Schema::create('users_lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_lessons');
            $table->string('fk_users', 36);

            $table->boolean('completed');

            $table->primary(['fk_lessons', 'fk_users']);

            $table->foreign('fk_lessons')->references('les_serial')->on('lessons');
            $table->foreign('fk_users')->references('user_id')->on('users');
        });

        Schema::create('subjects_courses', function (Blueprint $table) {
            $table->unsignedInteger('fk_subjects');
            $table->string('fk_courses', 12);

            $table->primary(['fk_subjects', 'fk_courses']);

            $table->foreign('fk_courses')->references('cou_token')->on('courses');
            $table->foreign('fk_subjects')->references('sub_serial')->on('subjects');
        });

        Schema::create('categories_courses', function (Blueprint $table) {
            $table->unsignedInteger('fk_categories');
            $table->string('fk_courses', 12);

            $table->primary(['fk_categories', 'fk_courses']);

            $table->foreign('fk_categories')->references('cat_serial')->on('categories');
            $table->foreign('fk_courses')->references('cou_token')->on('courses');
        });

        Schema::create('subjects_materials', function (Blueprint $table) {
            $table->unsignedInteger('fk_subjects');
            $table->unsignedInteger('fk_materials');

            $table->primary(['fk_subjects', 'fk_materials']);

            $table->foreign('fk_subjects')->references('sub_serial')->on('subjects');
            $table->foreign('fk_materials')->references('mat_serial')->on('materials');
        });

        Schema::create('categories_materials', function (Blueprint $table) {
            $table->unsignedInteger('fk_categories');
            $table->unsignedInteger('fk_materials');

            $table->primary(['fk_categories', 'fk_materials']);

            $table->foreign('fk_categories')->references('cat_serial')->on('categories');
            $table->foreign('fk_materials')->references('mat_serial')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_medals');
        Schema::dropIfExists('courses_users');
        Schema::dropIfExists('users_lessons');
        Schema::dropIfExists('subjects_courses');
        Schema::dropIfExists('categories_courses');
        Schema::dropIfExists('subjects_materials');
        Schema::dropIfExists('categories_materials');
    }
};
