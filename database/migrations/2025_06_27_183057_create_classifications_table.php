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
            $table->tinyIncrements('id');
            $table->enum('name', ['Perdesaan', 'Perkotaan'])->unique();
            $table->timestamps();
        });

        // provinsi
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->char('code', 2)->unique(); // 2 digit prov
            $table->string('name');
            $table->timestamps();
        });

        // kabupaten
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->char('code', 4)->unique(); // 2 digit prov + 2 digit kab
            $table->string('name');
            $table->timestamps();
        });

        // kecamatan
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regency_id')->constrained()->onDelete('cascade');
            $table->char('code', 7); // 4 digit kab + 3 digit kec
            $table->string('name')->unique();
            $table->timestamps();
        });

        // desa
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');

            $table->unsignedTinyInteger('classification_id');
            $table->foreign('classification_id')->references('id')->on('classifications');

            $table->char('code', 11)->unique(); // 7 digit kec + 4 digit desa
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('villages');
    }
};
