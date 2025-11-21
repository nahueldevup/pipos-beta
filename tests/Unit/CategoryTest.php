<?php

use App\Models\Category;
use App\Models\Product;

test('puede crear una categoria', function () {
    $category = Category::create([
        'name' => 'Electrónica',
        'active' => true,
    ]);

    expect($category->id)->not->toBeNull();
    expect($category->name)->toBe('Electrónica');
    expect($category->active)->toBeTrue();
});

test('categoria tiene muchos productos', function () {
    $category = Category::factory()->create();
    Product::factory()->count(2)->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(2);
});
