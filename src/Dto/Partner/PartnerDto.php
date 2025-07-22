<?php

declare(strict_types=1);

namespace App\Dto\Partner;

use App\Dto\ImageDto;

final class PartnerDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $site_web,
        public readonly ImageDto $image,
    ) {
    }
}
