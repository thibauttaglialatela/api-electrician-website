<?php

declare(strict_types=1);

namespace App\Dto;

final class ImageDto
{
    public function __construct(
        public readonly string $url,
        public readonly string $alt,
    ) {
    }
}
