<?php

declare(strict_types=1);

namespace App\Enums;

enum CropOrigin: string
{
    case Seed = 'seed';
    case Seedling = 'seedling';
    case Cutting = 'cutting';
}

