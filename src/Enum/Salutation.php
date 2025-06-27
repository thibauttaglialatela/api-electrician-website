<?php

declare(strict_types=1);

namespace App\Enum;

enum Salutation: string
{
    case MR = 'M.';
    case MRS = 'Mme.';
    case SOCIETY = 'Entreprise';
}