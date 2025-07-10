<?php

declare(strict_types=1);

namespace App\Dto\Work;

final class LastWorkListDto
{
    public function __construct(
        public readonly int $id,
        public readonly ClientDto $client,
        public readonly string $endDate,
        public readonly ImageDto $image,
    ) {
    }
}
