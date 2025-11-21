<?php

use App\Models\Product;
use App\Models\Category;

test('puede ver lista de productos', function () {
    Product::factory()->count(3)->create(['active' => true]);

    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
    $response->assertViewIs('products.index');
});

test('puede crear un producto', function () {
    $category = Category::factory()->create();

    $productData = [
        'barcode' => '1234567890',
        'name' => 'Producto Nuevo',
        'description' => 'Descripción',
        'purchase_price' => 50.00,
        'sale_price' => 100.00,
        'stock' => 10,
        'min_stock' => 5,
        'category_id' => $category->id,
        'active' => true,
    ];

    $response = $this->post(route('products.store'), $productData);

    expect($response->status())->toBe(302);
    $this->assertDatabaseHas('products', [
        'barcode' => '1234567890',
        'name' => 'Producto Nuevo',
    ]);
});

test('valida barcode unico al crear', function () {
    Product::factory()->create(['barcode' => '123456']);

    $productData = [
        'barcode' => '123456',
        'name' => 'Producto',
        'sale_price' => 100,
        'stock' => 10,
        'min_stock' => 5,
    ];

    $response = $this->post(route('products.store'), $productData);

    $response->assertSessionHasErrors('barcode');
});