<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SunExposure;
use App\Enums\WaterDemand;
use App\Models\Species;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->string('search'));
        $waterDemand = $request->enum('water_demand', WaterDemand::class);
        $sunExposure = $request->enum('sun_exposure', SunExposure::class);

        $species = Species::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%' . mb_strtolower($search) . '%';

                $query->where(function (Builder $subQuery) use ($term): void {
                    $subQuery
                        ->whereRaw('LOWER(common_name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(COALESCE(scientific_name, \'\')) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(family) LIKE ?', [$term]);
                });
            })
            ->when($waterDemand !== null, function (Builder $query) use ($waterDemand): void {
                $query->where('water_demand', $waterDemand->value);
            })
            ->when($sunExposure !== null, function (Builder $query) use ($sunExposure): void {
                $query->where('sun_exposure', $sunExposure->value);
            })
            ->orderBy('common_name')
            ->get();

        return Inertia::render('Catalog/Index', [
            'species' => $species,
            'filters' => [
                'search' => $search !== '' ? $search : null,
                'water_demand' => $waterDemand?->value,
                'sun_exposure' => $sunExposure?->value,
            ],
        ]);
    }
}

