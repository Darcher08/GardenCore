<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\CropLogType;
use App\Models\Crop;
use App\Models\CropLog;

class QuickWaterCropAction
{
    public function execute(Crop $crop, ?string $notes = null): CropLog
    {
        return $crop->logs()->create([
            'log_type' => CropLogType::Watering,
            'recorded_at' => now()->toDateString(),
            'notes' => $notes,
        ]);
    }
}

