<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SunExposure;
use App\Enums\WaterDemand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory;

    protected $table = 'species';

    protected $fillable = [
        'common_name',
        'scientific_name',
        'family',
        'sun_exposure',
        'water_demand',
        'min_root_depth_cm',
        'recommended_volume_liters',
        'days_to_germination',
        'days_to_harvest',
        'companion_allies',
        'companion_antagonists',
    ];

    protected function casts(): array
    {
        return [
            'sun_exposure' => SunExposure::class,
            'water_demand' => WaterDemand::class,
            'min_root_depth_cm' => 'integer',
            'recommended_volume_liters' => 'integer',
            'days_to_germination' => 'integer',
            'days_to_harvest' => 'integer',
            'companion_allies' => 'array',
            'companion_antagonists' => 'array',
        ];
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class);
    }
}

