<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasEncryptedRouteKey;

class Fasyankes extends Model
{
    use HasEncryptedRouteKey;

    protected $table = 'fasyankes';

    protected $fillable = [
        'name',
        'type',
        'province_id',
        'regency_id',
        'address',
        'latitude',
        'longitude',
        'bed_capacity',
        'tps_permit_status',
        'storage_method',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'bed_capacity' => 'integer',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function wasteGenerations(): HasMany
    {
        return $this->hasMany(WasteGeneration::class);
    }

    public function latestWasteGeneration()
    {
        return $this->hasOne(WasteGeneration::class)->latestOfMany();
    }
}
