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
        Schema::table('menstrual_cycles', function (Blueprint $table) {
            // Tandai kapan notifikasi haid terlalu lama dikirim
            $table->timestamp('period_prolonged_notified_at')->nullable()->after('finish_date');
            // Tandai kapan notifikasi siklus tidak teratur (terlalu cepat/lambat) dikirim
            $table->timestamp('cycle_irregularity_notified_at')->nullable()->after('period_prolonged_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menstrual_cycles', function (Blueprint $table) {
            $table->dropColumn(['period_prolonged_notified_at', 'cycle_irregularity_notified_at']);
        });
    }
};
