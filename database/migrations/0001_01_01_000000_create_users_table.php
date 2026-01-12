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
        Schema::create('Users', function (Blueprint $table) {
            $table->uuid('user_id')->unique()->primary();
            $table->string('user_email', 255)->unique();
            $table->string('user_username', 40)->unique();
            $table->string('user_control_number', 10)->nullable();
            $table->string('user_description', 300)->nullable();
            $table->string('user_path_profile_picture', 255)->nullable();
            $table->string('user_path_banner', 255)->nullable();
            $table->char('user_pass', 60)->nullable();
            $table->unsignedTinyInteger('user_type');
            $table->string('user_name', 50);
            $table->string('user_surname', 40)->nullable();
            $table->string('user_second_surname', 40)->nullable();
            $table->timestampsTz();
        });

        Schema::create('Users_temp', function (Blueprint $table) {
            $table->string('user_email', 255);
            $table->string('token', 32)->nullable();
            $table->unsignedTinyInteger('user_type');
            $table->unsignedTinyInteger('request_type');
            $table->timestampsTz();
        });

        Schema::create('Collaborators', function (Blueprint $table) {
            $table->string('col_email', 255)->primary();
            $table->string('col_description', 300)->nullable();
            $table->string('col_path_profile_picture', 255)->nullable();
            $table->string('col_url_linkedin', 255)->nullable();
            $table->string('col_url_website', 255)->nullable();
            $table->string('col_names', 50);
            $table->string('col_surname', 40)->nullable();
            $table->string('col_second_surname', 40)->nullable();
            $table->timestampsTz();
        });

        Schema::create('Medals', function (Blueprint $table) {
            $table->unsignedInteger('med_serial')->primary();
            $table->string('med_name', 80);
            $table->string('med_description', 300)->nullable();
            $table->string('med_path_image', 255)->nullable();
            $table->timestampsTz();
        });

        # No es necesaria, pero guardare por ahora como referencia
        #Schema::create('sessions', function (Blueprint $table) {
        #    $table->string('id')->primary();
        #    $table->foreignId('user_id')->nullable()->index();
        #    $table->string('ip_address', 45)->nullable();
        #    $table->text('user_agent')->nullable();
        #    $table->longText('payload');
        #    $table->integer('last_activity')->index();
        #});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('Usuarios_temp');
        #Schema::dropIfExists('password_reset_tokens');
        #Schema::dropIfExists('sessions');
    }
};
