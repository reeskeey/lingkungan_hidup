<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasyankes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('type', 50); // RS Kelas A, B, C, D, Puskesmas, Klinik
            $table->foreignId('province_id')->constrained('provinces')->cascadeOnDelete();
            $table->foreignId('regency_id')->constrained('regencies')->cascadeOnDelete();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->integer('bed_capacity')->default(0);
            $table->string('tps_permit_status', 50)->default('Belum Memiliki Izin'); // Memiliki Izin, Dalam Proses, Belum Memiliki Izin
            $table->string('storage_method', 100)->default('TPS B3 Standar'); // Ruang Berpendingin/Cold Storage, TPS B3 Standar, Penyimpanan Sederhana
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('type');
            $table->index('tps_permit_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasyankes');
    }
};
