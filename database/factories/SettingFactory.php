<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group' => $this->faker->word(),
            'name' => $this->faker->unique()->word(),
            'locked' => $this->faker->boolean(20),
            'payload' => [
                'key1' => $this->faker->word(),
                'key2' => $this->faker->numberBetween(1, 100),
            ],
        ];
    }

    /**
     * Estado para la configuración general del sistema
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'group' => 'general',
            'name' => 'general',
            'locked' => false,
            'payload' => \App\Models\Setting::defaultGeneralPayload(),
        ]);
    }
}
