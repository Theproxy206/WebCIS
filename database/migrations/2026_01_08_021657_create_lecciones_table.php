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
        Schema::create('lecciones', function (Blueprint $table) {
            $table->id('lec_serial');
            $table->longText('lec_contenido');
            $table->string('lec_titulo', 200);
            $table->string('lec_titulo_corto', 60);
            $table->dateTime('lec_ultima_actualizacion');
            $table->foreignId('fk_lecciones_lecciones')
                ->nullable()
                ->index()
                ->constrained('lecciones', 'lec_serial')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('fk_lecciones_cursos')
                ->nullable()
                ->index()
                ->constrained('cursos', 'cur_serial')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignUuid('fk_lecciones_usuarios')
                ->nullable()
                ->index()
                ->constrained('usuarios', 'usu_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};
