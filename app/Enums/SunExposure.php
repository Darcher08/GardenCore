<?php

declare(strict_types=1);

namespace App\Enums;

enum SunExposure: string
{
    case FullSun = 'full_sun';
    case PartialShade = 'partial_shade';
    case FullShade = 'full_shade';
}

