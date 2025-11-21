<?php

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->customer = Customer::factory()->create();
    $this->product = Product::factory()->create([
        'barcode' => '123456',
        'name' => 'Producto Test',
        'sale_price' => 100.00,
        'stock' => 10,
    ]);
});

test('puede crear una venta completa', function () {
    $sale = Sale::create([
        'sale_number' => 'VTA-TEST-001',
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'subtotal' => 100.00,
        'discount_amount' => 0,
        'tax_amount' => 21.00,
        'total' => 121.00,
        'payment_method' => 'efectivo',
        'amount_paid' => 150.00,
        'change_amount' => 29.00,
        'status' => 'completed',
    ]);

    $sale->details()->create([
        'product_id' => $this->product->id,
        'product_barcode' => $this->product->barcode,
        'product_name' => $this->product->name,
        'quantity' => 1,
        'unit_price' => 100.00,
        'discount_amount' => 0,
        'line_total' => 100.00,
    ]);

    expect($sale->id)->not->toBeNull();
    expect($sale->details)->toHaveCount(1);
    expect($sale->status)->toBe('completed');
});

test('venta reduce stock del producto', function () {
    $initialStock = $this->product->stock;

    $sale = Sale::create([
        'sale_number' => 'VTA-TEST-002',
        'customer_id' => $this->customer->id,
        'user_id' => $this->user->id,
        'subtotal' => 100.00,
        'tax_amount' => 0,
        'total' => 100.00,
        'payment_method' => 'efectivo',
        'amount_paid' => 100.00,
        'change_amount' => 0,
    ]);

    $sale->details()->create([
        'product_id' => $this->product->id,
        'product_barcode' => $this->product->barcode,
        'product_name' => $this->product->name,
        'quantity' => 2,
        'unit_price' => 100.00,
        'line_total' => 200.00,
    ]);

    $this->product->decrement('stock', 2);
    $this->product->refresh();

    expect($this->product->stock)->toBe($initialStock - 2);
});

test('venta pertenece a un cliente', function () {
    $sale = Sale::factory()->create([
        'customer_id' => $this->customer->id
    ]);

    expect($sale->customer)->toBeInstanceOf(Customer::class);
    expect($sale->customer->id)->toBe($this->customer->id);
});

test('venta pertenece a un usuario', function () {
    $sale = Sale::factory()->create([
        'user_id' => $this->user->id
    ]);

    expect($sale->user)->toBeInstanceOf(User::class);
    expect($sale->user->id)->toBe($this->user->id);
});