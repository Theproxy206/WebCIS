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
        Schema::create('subjects', function (Blueprint $table) {
            $table->unsignedInteger('sub_serial', true)->primary();
            $table->char('sub_code', 8);
            $table->string('sub_name', 100);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedInteger('cat_serial', true)->primary();
            $table->string('cat_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('categories');
    }
};
