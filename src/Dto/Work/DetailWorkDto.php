<?php

declare(strict_types=1);

namespace App\Dto\Work;

use App\Dto\ClientDto;
use App\Dto\ImageDto;

final class DetailWorkDto
{
    /** @param ImageDto[] $images */
    public function __construct(
        public readonly int $id,
        public readonly ClientDto $client,
        public readonly string $description,
        public readonly \DateTimeImmutable $startDate,
        public readonly ?\DateTimeImmutable $endDate = null,
        public readonly ?int $durationInDays = 0,
        public readonly array $images = [],
    ) {
    }
}
