<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CropLogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'log_type',
        'recorded_at',
        'notes',
        'metrics',
    ];

    protected function casts(): array
    {
        return [
            'log_type' => CropLogType::class,
            'recorded_at' => 'date',
            'metrics' => 'array',
        ];
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }
}

