<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Crop;
use App\Models\Species;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CheckCompanionConflictAction
{
    /**
     * Evalúa conflictos botánicos bidireccionales entre cultivos activos del mismo contenedor.
     *
     * @param  Collection<int, Crop>|null  $containerActiveCrops
     */
    public function execute(Crop $crop, ?Collection $containerActiveCrops = null): ?string
    {
        if (! $crop->is_active || $crop->container_id === null) {
            return null;
        }

        $crop->loadMissing('species');

        if ($crop->species === null) {
            return null;
        }

        $siblings = $containerActiveCrops ?? Crop::query()
            ->where('container_id', $crop->container_id)
            ->where('is_active', true)
            ->whereKeyNot($crop->getKey())
            ->with('species')
            ->get();

        $conflictingSpeciesNames = [];

        foreach ($siblings as $sibling) {
            if ($sibling->getKey() === $crop->getKey() || ! $sibling->is_active) {
                continue;
            }

            $sibling->loadMissing('species');

            if ($sibling->species === null) {
                continue;
            }

            if (
                $this->isAntagonist($crop->species, $sibling->species)
                || $this->isAntagonist($sibling->species, $crop->species)
            ) {
                $conflictingSpeciesNames[] = $sibling->species->common_name;
            }
        }

        $conflictingSpeciesNames = array_values(array_unique($conflictingSpeciesNames));

        if ($conflictingSpeciesNames === []) {
            return null;
        }

        return sprintf(
            'Alerta de incompatibilidad botánica: %s presenta conflicto con %s en el mismo contenedor.',
            $crop->species->common_name,
            implode(', ', $conflictingSpeciesNames)
        );
    }

    private function isAntagonist(Species $source, Species $target): bool
    {
        $antagonists = $source->companion_antagonists ?? [];

        if ($antagonists === []) {
            return false;
        }

        $normalizedTargetTerms = array_filter([
            (string) $target->getKey(),
            Str::lower(trim($target->common_name)),
            $target->scientific_name ? Str::lower(trim($target->scientific_name)) : null,
            Str::lower(trim($target->family)),
        ]);

        foreach ($antagonists as $antagonist) {
            $normalizedAntagonist = Str::lower(trim((string) $antagonist));

            if (in_array($normalizedAntagonist, $normalizedTargetTerms, true)) {
                return true;
            }
        }

        return false;
    }
}

