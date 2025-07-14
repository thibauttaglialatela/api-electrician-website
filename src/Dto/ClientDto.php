<?php

declare(strict_types=1);

namespace App\Dto;

final class ClientDto
{
    public function __construct(
        public readonly string $displayName,
    ) {
    }
}
