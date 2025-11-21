<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_producto()
    {
        $product = Product::create([
            'barcode' => '111222333',
            'name' => 'Coca Cola',
            'sale_price' => 100.00,
            'stock' => 10,
            'active' => true,
        ]);

        $this->assertEquals('Coca Cola', $product->name);
        $this->assertTrue($product->active);
    }

    /** @test */
    public function producto_tiene_categoria()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $this->assertInstanceOf(Category::class, $product->category);
    }
}