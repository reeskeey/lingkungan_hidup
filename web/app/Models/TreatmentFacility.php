<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasEncryptedRouteKey;

class TreatmentFacility extends Model
{
    use HasEncryptedRouteKey;

    protected $fillable = [
        'name',
        'facility_type',
        'operator_category',
        'province_id',
        'regency_id',
        'latitude',
        'longitude',
        'installed_capacity_kg_h',
        'licensed_capacity_ton_day',
        'permit_number',
        'operational_status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'installed_capacity_kg_h' => 'float',
        'licensed_capacity_ton_day' => 'float',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }
}
