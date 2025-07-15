<?php

declare(strict_types=1);

namespace App\Dto\Work;

use App\Dto\ClientDto;
use App\Dto\ImageDto;

final class LastWorkListDto
{
    public function __construct(
        public readonly int $id,
        public readonly ClientDto $client,
        public readonly \DateTimeImmutable $endDate,
        public readonly ImageDto $image,
    ) {
    }
}
