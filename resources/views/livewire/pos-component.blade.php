<div>
    {{-- Para que los estilos y scripts se carguen en el layout principal --}}
    @push('styles')
        @livewireStyles
    @endpush

    <h2>🛒 Punto de Venta</h2>
    <br>

    <div style="display: flex; gap: 2em;">

        <div style="flex: 1;">
            {{-- Componente Hijo para la Búsqueda --}}
            <livewire:product-search />
        </div>

        <div style="flex: 1; border: 1px solid #ccc; padding: 1em; border-radius: 8px;">
            {{-- Componente Hijo para el Carrito --}}
            <livewire:cart />
        </div>

    </div>

    @push('scripts')
        @livewireScripts
    @endpush
</div>
