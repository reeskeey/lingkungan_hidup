<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedRouteKey;

class RoadmapAction extends Model
{
    use HasEncryptedRouteKey;

    protected $fillable = [
        'program_name',
        'baseline',
        'target',
        'priority_location',
        'time_horizon',
        'responsible_agency',
        'supporting_agency',
        'indicative_budget',
        'kpi',
        'program_output',
        'funding_source',
        'progress_percent',
    ];

    protected $casts = [
        'indicative_budget' => 'float',
        'progress_percent' => 'integer',
    ];
}
