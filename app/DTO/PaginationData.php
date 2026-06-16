<?php

declare(strict_types=1);

namespace App\DTO;

final class PaginationData
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
    ) {}
}
