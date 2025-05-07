<?php

namespace App\Repositories;

use App\DTO\ProductDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{

    public function create(ProductDTO $dto): Product
    {
        return Product::create([
            'id' => Str::uuid(),
            ...get_object_vars($dto),
        ]);
    }

    public function update(string $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function find(string $id): ?Product
    {
        return Product::findOrFail($id);
    }

    public function delete(string $id): bool
    {
        return Product::where('id', $id)->delete() > 0;
    }

    public function filter(array $filters): LengthAwarePaginator
    {
        return Product::query()
            ->when($filters['category'] ?? null, fn($q, $v) => $q->where('category', $v))
            ->when($filters['min_price'] ?? null, fn($q, $v) => $q->where('price', '>=', $v))
            ->when($filters['max_price'] ?? null, fn($q, $v) => $q->where('price', '<=', $v))
            ->paginate($filters['per_page'] ?? 15);
    }
}
