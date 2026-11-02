<?php

declare(strict_types=1);

namespace App\Enums;

enum CropStage: string
{
    case Germinating = 'germinating';
    case Vegetative = 'vegetative';
    case Flowering = 'flowering';
    case Harvesting = 'harvesting';
    case Terminated = 'terminated';
}

