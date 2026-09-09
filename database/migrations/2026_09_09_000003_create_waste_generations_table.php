<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fasyankes_id')->constrained('fasyankes')->cascadeOnDelete();
            $table->year('year')->default(2026);
            $table->decimal('daily_generation_kg', 10, 2)->default(0.00);
            $table->decimal('annual_generation_ton', 12, 2)->default(0.00);
            $table->decimal('infectious_kg', 10, 2)->default(0.00);
            $table->decimal('sharps_kg', 10, 2)->default(0.00);
            $table->decimal('pathological_kg', 10, 2)->default(0.00);
            $table->decimal('chemical_pharmaceutical_kg', 10, 2)->default(0.00);
            $table->string('management_method', 100)->default('Kerjasama Pengolah Berizin Pihak ke-3');
            $table->timestamps();

            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_generations');
    }
};
