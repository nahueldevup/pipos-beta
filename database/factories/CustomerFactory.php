<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->optional()->numerify('##########'),
        ];
    }

    /**
     * Cliente sin teléfono
     */
    public function withoutPhone()
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
        ]);
    }
}
