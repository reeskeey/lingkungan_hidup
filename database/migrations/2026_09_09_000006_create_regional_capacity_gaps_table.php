<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regional_capacity_gaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->year('year')->default(2026);
            $table->decimal('total_waste_ton_day', 10, 2)->default(0.00);
            $table->decimal('total_treatment_capacity_ton_day', 10, 2)->default(0.00);
            $table->decimal('capacity_gap_ton_day', 10, 2)->default(0.00); // Kapasitas - Timbulan (+ surplus, - defisit)
            $table->decimal('coverage_ratio_percent', 8, 2)->default(0.00);
            $table->string('status', 50)->default('Defisit Kritis'); // Defisit Kritis, Defisit Sedang, Surplus
            $table->string('priority_level', 50)->default('Prioritas 2'); // Prioritas 1 (Mendesak), Prioritas 2, Prioritas 3
            $table->timestamps();

            $table->unique(['province_id', 'year']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regional_capacity_gaps');
    }
};
