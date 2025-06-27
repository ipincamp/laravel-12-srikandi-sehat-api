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
        // klasifikasi
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Perdesaan', 'Perkotaan'])->unique();
            $table->timestamps();
        });

        // kecamatan
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // desa
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->char('code', 3);
            $table->string('name')->unique();
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignId('classification_id')->constrained('classifications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('villages');
    }
};
