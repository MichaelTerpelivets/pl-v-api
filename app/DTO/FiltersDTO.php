<?php

namespace App\DTO;

/**
 * Data Transfer Object for filtering products.
 */
readonly class FiltersDTO
{
    public function __construct(
        public ?string $category = null,
        public ?float  $min_price = null,
        public ?float  $max_price = null,
        public int     $per_page = 15
    )
    {
    }

}
