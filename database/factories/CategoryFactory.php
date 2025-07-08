<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'parent_id' => null,
            'is_active' => fake()->boolean(90),
        ];
    }

    public function withParent($parentId): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId,
        ]);
    }
}