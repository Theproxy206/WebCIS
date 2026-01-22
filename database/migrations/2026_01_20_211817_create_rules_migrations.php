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
        Schema::create('rules', function (Blueprint $table) {
            $table->id('rule_serial');
            $table->string('rule_name');
            $table->timestamps();
        });

        Schema::create('users_rules', function (Blueprint $table) {
            $table->foreignUuid('fk_users')->references('user_id')->on('users');
            $table->foreignId('fk_rules')->constrained('rules');
            $table->boolean('granted')->default(false);

            $table->primary(['fk_users', 'fk_rules']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
