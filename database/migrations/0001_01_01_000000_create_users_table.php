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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('usu_id')->unique()->primary();
            $table->string('usu_correo_electronico', 255)->unique();
            $table->string('usu_nombre_de_usuario', 40)->unique();
            $table->string('usu_numero_de_control', 10)->nullable();
            $table->string('usu_descripcion', 300)->nullable();
            $table->string('usu_path_foto_de_perfil', 255)->nullable();
            $table->string('usu_path_img_portada', 255)->nullable();
            $table->char('usu_pass', 60)->nullable();
            $table->unsignedTinyInteger('usu_tipo');
            $table->string('usu_primer_nombre', 50);
            $table->string('usu_primer_apellido', 40)->nullable();
            $table->string('usu_segundo_apellido', 40)->nullable();
        });

        Schema::create('Usuarios_temp', function (Blueprint $table) {
            $table->string('usu_correo_electronico', 255);
            $table->string('token', 32)->nullable();
            $table->unsignedTinyInteger('tipo_usu');
            $table->dateTime('fecha_creacion');
            $table->unsignedTinyInteger('tipo_request');
        });

        # Sujeta a debate
        #Schema::create('password_reset_tokens', function (Blueprint $table) {
        #    $table->string('email')->primary();
        #    $table->string('token');
        #    $table->timestamp('created_at')->nullable();
        #});

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
