<?php

declare(strict_types=1);

namespace App\Enums;

enum ContainerType: string
{
    case Pot = 'pot';
    case GrowBag = 'grow_bag';
    case RaisedBed = 'raised_bed';
    case GroundSpot = 'ground_spot';
}

