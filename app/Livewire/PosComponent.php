<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PosComponent extends Component
{
    // ----- PROPIEDADES PARA LA BÚSQUEDA -----
    public $search = '';

    // ----- PROPIEDADES PARA LA VENTA -----
    public $cart = [];
    public $subtotal = 0;
    public $tax_amount = 0;      // ← NUEVO
    public $tax_rate = 0;        // ← NUEVO
    public $total = 0;
    public $customer_id;

    // Propiedades nuevas basadas en tu migración
    public $payment_method = 'efectivo';
    public $amount_paid;
    public $change_amount = 0;

    // ← NUEVO MÉTODO
    public function mount()
    {
        $settings = app(GeneralSettings::class);
        $this->tax_rate = $settings->tax_rate;
    }

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

        $customers = Customer::all();

        return view('livewire.pos-component', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function addProduct(Product $product)
    {
        $quantityInCart = $this->cart[$product->id]['quantity'] ?? 0;

        if ($product->stock <= $quantityInCart) {
            session()->flash('stock_error', 'No hay más stock para: ' . $product->name);
            return;
        }

        if (isset($this->cart[$product->id])) {
            $this->cart[$product->id]['quantity']++;
        } else {
            $this->cart[$product->id] = [
                'name' => $product->name,
                'barcode' => $product->barcode,
                'price' => $product->sale_price,
                'quantity' => 1,
                'stock' => $product->stock
            ];
        }
        $this->calculateTotals();
        $this->search = '';
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotals();
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'cart.')) {
            $parts = explode('.', $propertyName);
            $productId = $parts[1];
            $property = $parts[2];

            if ($property === 'quantity') {
                $item = $this->cart[$productId];
                if ($item['quantity'] > $item['stock']) {
                    $this->cart[$productId]['quantity'] = $item['stock'];
                    session()->flash('stock_error', 'Stock máximo alcanzado para: ' . $item['name']);
                }
                if ($item['quantity'] < 1) {
                    $this->cart[$productId]['quantity'] = 1;
                }
            }

            $this->calculateTotals();
        }

        if ($propertyName == 'amount_paid' && !empty($this->amount_paid)) {
            $this->change_amount = $this->amount_paid - $this->total;
        }
    }

    public function saveSale()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'cart' => 'required|array|min:1',
            'payment_method' => 'required',
            'amount_paid' => 'required|numeric|min:' . $this->total,
        ]);

        $sale = null;

        DB::transaction(function () use (&$sale) {
            $sale = Sale::create([
                'sale_number' => 'VTA-' . date('Ymd') . '-' . uniqid(),
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id() ?? 1,
                'subtotal' => $this->subtotal,
                'discount_amount' => 0,
                'tax_amount' => $this->tax_amount, // ← NUEVO
                'total' => $this->total,
                'payment_method' => $this->payment_method,
                'amount_paid' => $this->amount_paid,
                'change_amount' => $this->change_amount,
                'status' => 'completed',
            ]);

            foreach ($this->cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('No hay suficiente stock para el producto: ' . $product->name);
                }

                $lineTotal = $item['price'] * $item['quantity'];

                $sale->details()->create([
                    'product_id' => $productId,
                    'product_barcode' => $item['barcode'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'discount_amount' => 0,
                    'line_total' => $lineTotal,
                ]);

                $product->decrement('stock', $item['quantity']);
            }
        });

        if ($sale) {
            $this->resetState();
            return redirect()->route('sales.show', $sale);
        } else {
            session()->flash('error', 'Ocurrió un error inesperado al guardar la venta.');
        }
    }

    // ← MÉTODO MODIFICADO
    private function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * ($item['quantity'] > 0 ? $item['quantity'] : 1);
        }

        // ← NUEVO: Calcular impuestos
        $this->tax_amount = ($this->subtotal * $this->tax_rate) / 100;

        // ← NUEVO: Total = Subtotal + Impuestos
        $this->total = $this->subtotal + $this->tax_amount;

        $this->change_amount = 0;
        $this->amount_paid = '';
    }

    private function resetState()
    {
        $this->cart = [];
        $this->subtotal = 0;
        $this->tax_amount = 0; // ← NUEVO
        $this->total = 0;
        $this->customer_id = null;
        $this->search = '';
        $this->payment_method = 'efectivo';
        $this->amount_paid = null;
        $this->change_amount = 0;
    }
}
