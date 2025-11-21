<?php
namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 500);
        $taxRate = 21; // 21%
        $taxAmount = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $taxAmount;
        $amountPaid = $total + $this->faker->randomFloat(2, 0, 50);

        return [
            'sale_number' => 'VTA-' . date('Ymd') . '-' . $this->faker->unique()->numerify('####'),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
            'payment_method' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'amount_paid' => round($amountPaid, 2),
            'change_amount' => round($amountPaid - $total, 2),
            'status' => 'completed',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Venta cancelada
     */
    public function cancelled()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Venta en efectivo
     */
    public function cash()
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'efectivo',
        ]);
    }

    /**
     * Venta con tarjeta
     */
    public function card()
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'tarjeta',
        ]);
    }
}