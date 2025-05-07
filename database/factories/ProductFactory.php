<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'id' => (string)Str::uuid(),
            'name' => $this->faker->word(),
            'slug' => Str::slug($name),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'category' => $this->faker->word(),
            'attributes' => ['size' => 'L', 'color' => 'red'],
        ];
    }
}
