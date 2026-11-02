<?php

declare(strict_types=1);

namespace App\Enums;

enum CropLogType: string
{
    case Watering = 'watering';
    case Feeding = 'feeding';
    case Pruning = 'pruning';
    case Pest = 'pest';
    case Treatment = 'treatment';
    case Harvest = 'harvest';
    case Note = 'note';
}

