<?php

declare(strict_types=1);

namespace App\Dto\Prestation;

use App\Dto\ImageDto;

final class PrestationDetailDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $tarif,
        public readonly string $description,
        public readonly ImageDto $image,
    ) {
    }
}
