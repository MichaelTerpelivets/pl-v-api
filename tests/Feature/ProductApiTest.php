<?php

namespace Tests\Feature;

use App\Enums\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected string $apiUrl = '/api/products';

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_create_product(): void
    {
        $payload = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['color' => 'red', 'size' => 'M'],
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'name', 'price', 'category', 'attributes'
            ])
            ->assertJson([
                'name' => $payload['name'],
                'price' => $payload['price'],
                'category' => $payload['category'],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => $payload['name'],
            'category' => $payload['category']
        ]);
    }

    public function test_update_product(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'name' => 'Updated Product Name',
            'price' => 200.00,
            'category' => Category::Clothing->value,
        ];

        $response = $this->putJson("{$this->apiUrl}/{$product->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $payload['name'],
                'price' => $payload['price'],
                'category' => $payload['category'],
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $payload['name'],
            'category' => $payload['category']
        ]);
    }

    public function test_get_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("{$this->apiUrl}/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $product->name,
            ]);
    }

    public function test_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("{$this->apiUrl}/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_filter_products(): void
    {
        Product::factory()->create(['category' => 'electronics', 'price' => 100]);
        Product::factory()->create(['category' => 'clothing', 'price' => 200]);

        $query = [
            'category' => 'electronics',
            'min_price' => 50,
            'max_price' => 150,
        ];

        $response = $this->getJson($this->apiUrl . '?' . http_build_query($query));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'price', 'category', 'attributes'],
                ],
            ]);

        $responseData = $response->decodeResponseJson()['data'];

        $this->assertCount(1, $responseData);
        $this->assertEquals('electronics', $responseData[0]['category']);
    }


}
