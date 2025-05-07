<?php

namespace App\Repositories;

use App\DTO\ProductDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function create(ProductDTO $dto): Product;

    public function update(string $id, array $data): Product;

    public function find(string $id): ?Product;

    public function delete(string $id): bool;

    public function filter(array $filters): LengthAwarePaginator;
}
