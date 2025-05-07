<?php

namespace Tests\Unit;

use App\DTO\ProductDTO;
use App\Enums\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository();
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            price: 99.99,
            category: Category::Electronics->value,
            attributes: ['color' => 'black', 'size' => 'medium']
        );

        $product = $this->productRepository->create($dto);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Test Product',
            'slug' => $product->slug,
        ]);
    }

    /** @test */
    public function it_can_update_a_product(): void
    {
        $product = Product::factory()->create([
            'id' => (string)Str::uuid(),
            'name' => 'Old Name',
            'price' => 100.00,
            'category' => Category::Electronics->value,
        ]);
        $data = [
            'name' => 'New Name',
            'price' => 199.99,
        ];
        $updatedProduct = $this->productRepository->update($product->id, $data);

        $this->assertInstanceOf(Product::class, $updatedProduct);
        $this->assertEquals('New Name', $updatedProduct->name);
        $this->assertEquals(199.99, $updatedProduct->price);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'price' => 199.99,
        ]);
    }

    public function it_can_find_a_product_by_id(): void
    {
        $product = Product::factory()->create();
        $foundProduct = $this->productRepository->find($product->id);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($product->id, $foundProduct->id);
    }

    public function it_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $deleted = $this->productRepository->delete($product->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }


    public function it_can_filter_products_by_criteria(): void
    {
        Product::factory()->create([
            'id' => (string)Str::uuid(),
            'name' => 'Product A',
            'price' => 50.00,
            'category' => Category::Electronics->value,
        ]);
        Product::factory()->create([
            'id' => (string)Str::uuid(),
            'name' => 'Product B',
            'price' => 150.00,
            'category' => Category::Clothing->value,
        ]);
        Product::factory()->create([
            'id' => (string)Str::uuid(),
            'name' => 'Product C',
            'price' => 120.00,
            'category' => Category::Electronics->value,
        ]);

        $filters = [
            'category' => Category::Electronics->value,
            'min_price' => 100.00,
        ];


        $results = $this->productRepository->filter($filters);

        $this->assertCount(1, $results);
        $this->assertEquals('Product C', $results->first()->name);
    }
}
