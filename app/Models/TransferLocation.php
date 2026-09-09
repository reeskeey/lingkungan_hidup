<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasEncryptedRouteKey;

class TransferLocation extends Model
{
    use HasEncryptedRouteKey;

    protected $fillable = [
        'name',
        'province_id',
        'regency_id',
        'address',
        'latitude',
        'longitude',
        'holding_capacity_ton',
        'has_cold_storage',
        'service_status',
        'target_served_fasyankes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'holding_capacity_ton' => 'float',
        'has_cold_storage' => 'boolean',
        'target_served_fasyankes' => 'integer',
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
