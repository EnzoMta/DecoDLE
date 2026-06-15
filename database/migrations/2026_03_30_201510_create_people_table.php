<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('class')->nullable();
            $table->string('gender');     
            $table->integer('age');        
            $table->unsignedSmallInteger('height');              
            $table->string('hair_color');         
            $table->string('city');          
            $table->string('origin')->nullable();     
            $table->string('hobby');     
            $table->string('specialization');         
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};