<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CropOrigin;
use App\Enums\CropStage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'species_id',
        'container_id',
        'variety_name',
        'quantity',
        'planted_at',
        'origin',
        'stage',
        'terminated_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'planted_at' => 'date',
            'origin' => CropOrigin::class,
            'stage' => CropStage::class,
            'terminated_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CropLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}

