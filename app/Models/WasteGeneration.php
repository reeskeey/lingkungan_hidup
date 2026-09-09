<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteGeneration extends Model
{
    protected $fillable = [
        'fasyankes_id',
        'year',
        'daily_generation_kg',
        'annual_generation_ton',
        'infectious_kg',
        'sharps_kg',
        'pathological_kg',
        'chemical_pharmaceutical_kg',
        'management_method',
    ];

    protected $casts = [
        'year' => 'integer',
        'daily_generation_kg' => 'float',
        'annual_generation_ton' => 'float',
        'infectious_kg' => 'float',
        'sharps_kg' => 'float',
        'pathological_kg' => 'float',
        'chemical_pharmaceutical_kg' => 'float',
    ];

    public function fasyankes(): BelongsTo
    {
        return $this->belongsTo(Fasyankes::class);
    }
}
