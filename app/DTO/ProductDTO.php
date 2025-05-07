<?php

namespace App\DTO;

readonly class ProductDTO
{
    /**
     * @param string $name
     * @param float $price
     * @param string $category
     * @param array $attributes
     */
    public function __construct(
        public  string $name,
        public float  $price,
        public string $category,
        public array  $attributes,
    )
    {
    }
}
