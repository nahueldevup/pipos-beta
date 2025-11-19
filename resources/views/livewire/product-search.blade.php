<div>
    <h3>Buscar Productos</h3>
    <input
        wire:model.live.debounce.300ms="search"
        type="text"
        placeholder="Buscar por nombre o código de barras..."
        style="width: 100%;"
    >

    <hr>

    @if(count($products) > 0)
        <table class="table table-striped">
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }} (${{ number_format($product->sale_price, 2) }})</td>
                    <td style="text-align: right;">
                        {{-- Llama al método addProduct local --}}
                        <button class="btn btn-success" wire:click="addProduct({{ $product->id }})">
                            +
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @elseif(strlen($search) > 1)
        <p>No se encontraron productos...</p>
    @endif
</div>
