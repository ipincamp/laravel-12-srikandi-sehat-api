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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('category', [
                'Mood',
                'Fisik',
                'Lainnya',
                /*
                'dismenorea',
                'suasana-hati',
                '5L', // 5L: Lemah, Letih, Lesu, Lemas, Lunglai
                'anemia',
                'kram-perut',
                'nyeri-otot'
                */
            ]);
            $table->text('description')->nullable();
            $table->text('recommendation_txt')->nullable();
            $table->text('recommendation_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
    }
};
