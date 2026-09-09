<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regency extends Model
{
    protected $fillable = [
        'province_id',
        'code',
        'name',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function fasyankes(): HasMany
    {
        return $this->hasMany(Fasyankes::class);
    }

    public function treatmentFacilities(): HasMany
    {
        return $this->hasMany(TreatmentFacility::class);
    }

    public function transferLocations(): HasMany
    {
        return $this->hasMany(TransferLocation::class);
    }
}
