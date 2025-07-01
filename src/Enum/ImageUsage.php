<?php

declare(strict_types=1);

namespace App\Enum;

enum ImageUsage: string
{
    case WORK          = 'Chantier';
    case CERTIFICATION = 'Certification';
    case PRESTATION    = 'Prestation';
    case PARTNER       = 'Partenaire';
}
