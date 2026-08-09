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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('per_id');
            $table->string('per_code');
            $table->string('per_name');
            $table->string('per_description');
            $table->timestampsTz();
        });

        Schema::create('users_permissions', function (Blueprint $table) {
            $table->foreignUuid('fk_users')->references('user_id')->on('users');
            $table->foreignId('fk_permissions')->references('per_id')->on('permissions');
            $table->timestampsTz();

            $table->primary(['fk_users', 'fk_permissions']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('users_permissions');
    }
};
