<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('subjects', function (Blueprint $table) {
        $table->increments('sub_serial'); // PK
        $table->char('sub_official_key', 8); 
        $table->string('sub_name', 100);
        $table->timestamps();
    });
}
};
