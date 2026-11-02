<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContainerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'container_type',
        'volume_liters',
        'location_tag',
    ];

    protected function casts(): array
    {
        return [
            'container_type' => ContainerType::class,
            'volume_liters' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class);
    }

    public function activeCrops(): HasMany
    {
        return $this->hasMany(Crop::class)->where('is_active', true);
    }
}

