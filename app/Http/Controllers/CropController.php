<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AdvanceCropStageAction;
use App\Actions\CalculateEstimatedHarvestDateAction;
use App\Actions\CheckCompanionConflictAction;
use App\Actions\QuickWaterCropAction;
use App\Enums\CropStage;
use App\Http\Requests\AdvanceCropStageRequest;
use App\Http\Requests\StoreCropRequest;
use App\Models\Crop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CropController extends Controller
{
    public function store(StoreCropRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isTerminated = $validated['stage'] === CropStage::Terminated->value;

        Crop::query()->create([
            ...$validated,
            'user_id' => $request->user()->id,
            'is_active' => ! $isTerminated,
            'terminated_at' => $isTerminated ? now() : null,
        ]);

        return back()->with('success', 'Cultivo registrado correctamente.');
    }

    public function show(
        Request $request,
        Crop $crop,
        CalculateEstimatedHarvestDateAction $calculateHarvestAction,
        CheckCompanionConflictAction $checkConflictAction,
    ): Response {
        abort_unless($crop->user_id === $request->user()->id, 403);

        $crop->load([
            'species',
            'container',
            'logs' => fn ($query) => $query->latest('recorded_at')->latest('id'),
        ]);

        return Inertia::render('Crops/Show', [
            'crop' => array_merge($crop->toArray(), [
                'harvest_metrics' => $calculateHarvestAction->execute($crop),
                'companion_conflict' => $checkConflictAction->execute($crop),
            ]),
        ]);
    }

    public function advanceStage(
        AdvanceCropStageRequest $request,
        Crop $crop,
        AdvanceCropStageAction $advanceCropStageAction,
    ): RedirectResponse {
        abort_unless($crop->user_id === $request->user()->id, 403);

        $advanceCropStageAction->execute($crop, $request->validated('stage'));

        return back()->with('success', 'Etapa del cultivo actualizada.');
    }

    public function quickWater(
        Request $request,
        Crop $crop,
        QuickWaterCropAction $quickWaterCropAction,
    ): RedirectResponse {
        abort_unless($crop->user_id === $request->user()->id, 403);

        $quickWaterCropAction->execute($crop);

        return back()->with('success', 'Riego rápido registrado.');
    }
}

