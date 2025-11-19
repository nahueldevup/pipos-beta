<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductSearch extends Component
{
    public $search = '';

    public function render()
    {
        $search = trim($this->search);
        $products = [];

        if (!empty($search)) {
            $query = Product::active();

            if (is_numeric($search)) {
                $query->where('barcode', 'LIKE', '%' . $search . '%');
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('category', function($subQuery) use ($search) {
                            $subQuery->where('name', 'LIKE', '%' . $search . '%');
                        });
                });
            }

            $products = $query->limit(10)->get();
        }

        return view('livewire.product-search', [
            'products' => $products,
        ]);
    }

    /**
     * Emite un evento para que el carrito escuche.
     * No maneja el estado del carrito aquí.
     */
    public function addProduct(Product $product)
    {
        $this->dispatch('add-product', productId: $product->id);
        $this->search = ''; // Limpia la búsqueda después de añadir
    }
}
