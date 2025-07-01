<?php

declare(strict_types=1);

namespace App\DataFixtures;

final class FixtureData
{
    public const PRESTATIONS = ['Mise aux normes', 'Dépannage', 'Rénovation'];

    public const CERTIFICATIONS = [
        'RGE Qualifelec',
        'IRVE',
        'Qualibat',
    ];

    public const PARTNERS = [
        'Legrand',
        'Schneider Electric',
        'Rexel France',
        'Wallbox',
    ];
}
