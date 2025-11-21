<?php
namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleDetailFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $product->sale_price;
        $lineTotal = $unitPrice * $quantity;

        return [
            'sale_id' => Sale::factory(),
            'product_id' => $product->id,
            'product_barcode' => $product->barcode,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => 0,
            'line_total' => round($lineTotal, 2),
        ];
    }
}