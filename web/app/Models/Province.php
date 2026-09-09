<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasEncryptedRouteKey;

class Province extends Model
{
    use HasEncryptedRouteKey;

    protected $fillable = [
        'code',
        'name',
        'latitude',
        'longitude',
        'zoom_level',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'zoom_level' => 'integer',
    ];

    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class);
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

    public function capacityGap(): HasMany
    {
        return $this->hasMany(RegionalCapacityGap::class);
    }
}
