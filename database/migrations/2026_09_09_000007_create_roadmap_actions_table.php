<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmap_actions', function (Blueprint $table) {
            $table->id();
            $table->string('program_name', 255);
            $table->text('baseline')->nullable();
            $table->text('target')->nullable();
            $table->string('priority_location', 255)->default('Nasional');
            $table->string('time_horizon', 50); // Jangka Pendek (Tahun 1-2), Jangka Menengah (Tahun 3-5), Jangka Panjang (Tahun 6-10)
            $table->string('responsible_agency', 150)->default('KLH / BPLH');
            $table->string('supporting_agency', 255)->nullable();
            $table->decimal('indicative_budget', 18, 2)->default(0.00); // dalam Rupiah
            $table->text('kpi')->nullable();
            $table->text('program_output')->nullable();
            $table->string('funding_source', 100)->default('APBN KLH / DIPA');
            $table->integer('progress_percent')->default(0); // 0-100
            $table->timestamps();

            $table->index('time_horizon');
            $table->index('responsible_agency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_actions');
    }
};
