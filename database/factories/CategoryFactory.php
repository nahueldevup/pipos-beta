<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Electrónica',
                'Alimentos',
                'Bebidas',
                'Limpieza',
                'Papelería',
                'Juguetes',
                'Ropa',
                'Herramientas',
            ]),
            'active' => $this->faker->boolean(90), // 90% activas
        ];
    }

    /**
     * Indica que la categoría está activa
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    /**
     * Indica que la categoría está inactiva
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}
