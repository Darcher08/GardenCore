<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CalculateEstimatedHarvestDateAction;
use App\Actions\CheckCompanionConflictAction;
use App\Actions\QuickWaterCropAction;
use App\Models\Container;
use App\Models\Crop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class GardenController extends Controller
{
    public function dashboard(
        Request $request,
        CalculateEstimatedHarvestDateAction $calculateHarvestAction,
        CheckCompanionConflictAction $checkConflictAction,
    ): Response {
        $user = $request->user();

        $containers = Container::query()
            ->where('user_id', $user->id)
            ->with(['activeCrops.species'])
            ->orderBy('name')
            ->get()
            ->map(function (Container $container) use ($calculateHarvestAction, $checkConflictAction): array {
                $activeCrops = $container->activeCrops;

                $decoratedCrops = $activeCrops->map(
                    function (Crop $crop) use ($activeCrops, $calculateHarvestAction, $checkConflictAction): array {
                        return array_merge($crop->toArray(), [
                            'harvest_metrics' => $calculateHarvestAction->execute($crop),
                            'companion_conflict' => $checkConflictAction->execute($crop, $activeCrops),
                        ]);
                    }
                )->values();

                return array_merge($container->toArray(), [
                    'active_crops' => $decoratedCrops,
                ]);
            });

        $unassignedCrops = Crop::query()
            ->where('user_id', $user->id)
            ->whereNull('container_id')
            ->where('is_active', true)
            ->with('species')
            ->latest('planted_at')
            ->get()
            ->map(function (Crop $crop) use ($calculateHarvestAction): array {
                return array_merge($crop->toArray(), [
                    'harvest_metrics' => $calculateHarvestAction->execute($crop),
                    'companion_conflict' => null,
                ]);
            });

        return Inertia::render('Garden/Dashboard', [
            'containers' => $containers,
            'unassignedCrops' => $unassignedCrops,
        ]);
    }

    public function waterAllContainer(
        Request $request,
        Container $container,
        QuickWaterCropAction $quickWaterCropAction,
    ): RedirectResponse {
        abort_unless($container->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($container, $quickWaterCropAction): void {
            $activeCrops = $container->activeCrops()->get();

            foreach ($activeCrops as $crop) {
                $quickWaterCropAction->execute($crop);
            }
        });

        return back()->with('success', 'Todos los cultivos activos del contenedor han sido regados.');
    }
}

