<?php

declare(strict_types=1);

namespace App\Dto\Prestation;

use App\Dto\ImageDto;

final class PrestationDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $tarif,
        public readonly ImageDto $image,
    ) {
    }
}
