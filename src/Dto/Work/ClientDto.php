<?php

declare(strict_types=1);

namespace App\Dto\Work;

final class ClientDto
{
    public function __construct(
        public readonly string $displayName,
    ) {
    }
}
