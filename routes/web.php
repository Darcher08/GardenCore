<?php

declare(strict_types=1);

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\CropLogController;
use App\Http\Controllers\GardenController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    // Garden Dashboard & Container Batch Actions
    Route::get('/dashboard', [GardenController::class, 'dashboard'])->name('garden.dashboard');
    Route::post('/containers/{container}/water-all', [GardenController::class, 'waterAllContainer'])->name('containers.water-all');

    // Crops Management & Domain Actions
    Route::post('/crops', [CropController::class, 'store'])->name('crops.store');
    Route::get('/crops/{crop}', [CropController::class, 'show'])->name('crops.show');
    Route::patch('/crops/{crop}/advance-stage', [CropController::class, 'advanceStage'])->name('crops.advance-stage');
    Route::post('/crops/{crop}/quick-water', [CropController::class, 'quickWater'])->name('crops.quick-water');

    // Crop Logs
    Route::post('/crops/{crop}/logs', [CropLogController::class, 'store'])->name('crops.logs.store');

    // Botanical Species Catalog
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
});

