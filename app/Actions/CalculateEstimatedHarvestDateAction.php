<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Crop;
use Illuminate\Support\Carbon;

class CalculateEstimatedHarvestDateAction
{
    /**
     * @return array{
     *     days_elapsed: int,
     *     days_remaining: int,
     *     estimated_harvest_date: string,
     *     progress_percentage: int
     * }
     */
    public function execute(Crop $crop): array
    {
        $crop->loadMissing('species');

        $plantedAt = Carbon::parse($crop->planted_at)->startOfDay();
        $today = Carbon::today();
        $daysToHarvest = max(0, (int) ($crop->species?->days_to_harvest ?? 0));

        $estimatedHarvestDate = $plantedAt->copy()->addDays($daysToHarvest);

        $daysElapsed = max(0, (int) $plantedAt->diffInDays($today, false));
        $daysRemaining = max(0, (int) $today->diffInDays($estimatedHarvestDate, false));

        $progressPercentage = $daysToHarvest > 0
            ? min(100, (int) round(($daysElapsed / $daysToHarvest) * 100))
            : 100;

        return [
            'days_elapsed' => $daysElapsed,
            'days_remaining' => $daysRemaining,
            'estimated_harvest_date' => $estimatedHarvestDate->toDateString(),
            'progress_percentage' => $progressPercentage,
        ];
    }
}

