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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->foreignUuid('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('photo_path')->nullable();
            $table->foreignId('village_id')->nullable()->constrained('villages', 'id')->onDelete('set null');
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('height_cm')->unsigned()->nullable();
            $table->decimal('weight_kg', 5, 2)->unsigned()->nullable();
            $table->string('last_education')->nullable();
            $table->string('last_parent_education')->nullable();
            $table->string('last_parent_job')->nullable();
            $table->enum('internet_access', ['wifi', 'seluler'])->nullable();
            $table->integer('first_menstruation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
