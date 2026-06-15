<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');         
            $table->string('game_type');           
            $table->date('date');
            $table->integer('attempts')->default(0);
            $table->boolean('won')->default(false);
            $table->json('guesses')->nullable();   
            $table->timestamps();

            $table->unique(['session_id', 'game_type', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};