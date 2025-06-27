<?php

declare(strict_types=1);

namespace App\Enum;

enum SubjectMessage: string
{
    case DEVIS = 'demande devis';
    case INFOS = 'demande d\' informations';
}