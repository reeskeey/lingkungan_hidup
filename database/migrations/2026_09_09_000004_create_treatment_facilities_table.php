<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('facility_type', 50); // Insinerator Berizin, Autoklaf/Sterilisasi Berizin, Microwave
            $table->string('operator_category', 50); // Mandiri Fasyankes, Jasa Komersial Pihak Ketiga
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->foreignId('regency_id')->constrained('regencies')->cascadeOnDelete();
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->decimal('installed_capacity_kg_h', 10, 2)->default(0.00); // kg/jam
            $table->decimal('licensed_capacity_ton_day', 10, 2)->default(0.00); // ton/hari
            $table->string('permit_number', 100)->nullable();
            $table->string('operational_status', 50)->default('Aktif Beroperasi'); // Aktif Beroperasi, Pemeliharaan, Rencana Pengembangan
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('facility_type');
            $table->index('operator_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_facilities');
    }
};
