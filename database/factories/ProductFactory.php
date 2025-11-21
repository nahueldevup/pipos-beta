<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $purchasePrice = $this->faker->randomFloat(2, 10, 500);
        $salePrice = $purchasePrice * $this->faker->randomFloat(2, 1.2, 2.5);

        return [
            'barcode' => $this->faker->unique()->ean13(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'purchase_price' => $purchasePrice,
            'sale_price' => round($salePrice, 2),
            'stock' => $this->faker->numberBetween(0, 100),
            'min_stock' => $this->faker->numberBetween(5, 20),
            'category_id' => Category::factory(),
            'active' => $this->faker->boolean(95), // 95% activos
        ];
    }

    /**
     * Producto sin categoría
     */
    public function withoutCategory()
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => null,
        ]);
    }

    /**
     * Producto con stock bajo
     */
    public function lowStock()
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 2,
            'min_stock' => 10,
        ]);
    }

    /**
     * Producto sin stock
     */
    public function outOfStock()
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Producto activo
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }
}
