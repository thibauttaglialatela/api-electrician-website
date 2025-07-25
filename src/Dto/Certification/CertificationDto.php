<?php

declare(strict_types=1);

namespace App\Dto\Certification;

use App\Dto\ImageDto;

final class CertificationDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ImageDto $image,
    ) {
    }
}
