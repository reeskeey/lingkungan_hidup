<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalCapacityGap extends Model
{
    protected $fillable = [
        'province_id',
        'year',
        'total_waste_ton_day',
        'total_treatment_capacity_ton_day',
        'capacity_gap_ton_day',
        'coverage_ratio_percent',
        'status',
        'priority_level',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_waste_ton_day' => 'float',
        'total_treatment_capacity_ton_day' => 'float',
        'capacity_gap_ton_day' => 'float',
        'coverage_ratio_percent' => 'float',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
