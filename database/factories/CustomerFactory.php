<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'type' => $this->faker->randomElement(['regular', 'vip', 'wholesale']),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}