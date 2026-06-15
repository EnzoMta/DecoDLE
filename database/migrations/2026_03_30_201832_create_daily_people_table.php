<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('game_type');         
            $table->date('date');
            $table->timestamps();

            $table->unique(['game_type', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_people');
    }
};