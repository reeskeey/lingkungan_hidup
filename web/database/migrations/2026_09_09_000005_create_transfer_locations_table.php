<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->foreignId('regency_id')->constrained('regencies')->cascadeOnDelete();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->decimal('holding_capacity_ton', 10, 2)->default(0.00); // ton
            $table->boolean('has_cold_storage')->default(false);
            $table->string('service_status', 50)->default('Aktif Beroperasi'); // Aktif Beroperasi, Rencana
            $table->integer('target_served_fasyankes')->default(0);
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('service_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_locations');
    }
};
