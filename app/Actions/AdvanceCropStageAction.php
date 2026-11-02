<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\CropStage;
use App\Models\Crop;

class AdvanceCropStageAction
{
    public function execute(Crop $crop, CropStage|string $stage): Crop
    {
        $resolvedStage = $stage instanceof CropStage
            ? $stage
            : CropStage::from($stage);

        $attributes = [
            'stage' => $resolvedStage,
        ];

        if ($resolvedStage === CropStage::Terminated) {
            $attributes['is_active'] = false;
            $attributes['terminated_at'] = now();
        }

        $crop->update($attributes);

        return $crop->refresh();
    }
}

