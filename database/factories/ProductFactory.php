<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $price = fake()->randomFloat(2, 10, 500);
        $stock = fake()->numberBetween(0, 100);
        
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'sku' => strtoupper(fake()->bothify('??###??')),
            'price' => $price,
            'stock' => $stock,
            'min_stock' => fake()->numberBetween(1, 10),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'images' => [],
            'discount_percentage' => fake()->optional(0.3)->randomFloat(2, 5, 50),
            'discount_valid_until' => fake()->optional(0.3)->dateTimeBetween('now', '+6 months'),
            'is_active' => fake()->boolean(85),
        ];
    }

    public function withDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_percentage' => fake()->randomFloat(2, 10, 40),
            'discount_valid_until' => fake()->dateTimeBetween('now', '+3 months'),
        ]);
    }
}