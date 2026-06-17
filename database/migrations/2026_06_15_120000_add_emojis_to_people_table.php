<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('emoji_1')->nullable()->after('specialization');
            $table->string('emoji_2')->nullable()->after('emoji_1');
            $table->string('emoji_3')->nullable()->after('emoji_2');
            $table->string('emoji_4')->nullable()->after('emoji_3');
        });
    }

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(['emoji_1', 'emoji_2', 'emoji_3', 'emoji_4']);
        });
    }
};
