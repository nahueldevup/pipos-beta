@extends('layouts.app')

@section('content')
    @php
        // Obtener configuraciones usando el modelo
        $settings = \App\Models\SystemSetting::getSettings();
    @endphp

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden" id="ticket-content">
            <div class="p-6">
                {{-- Encabezado CON DATOS DE LA EMPRESA --}}
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-black">{{ $settings->company_name }}</h1>

                    @if($settings->company_address)
                        <p class="text-gray-600">{{ $settings->company_address }}</p>
                    @endif

                    @if($settings->company_phone)
                        <p class="text-gray-600">Tel: {{ $settings->company_phone }}</p>
                    @endif

                    <hr class="my-4">

                    <h2 class="text-xl font-semibold text-black">Ticket de Venta</h2>
                    <p class="text-gray-600">Comprobante N°: {{ $sale->sale_number }}</p>
                    <p class="text-gray-500 text-sm">Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>

                {{-- Datos de la Venta --}}
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div>
                        <h2 class="font-bold text-gray-800">Vendido por:</h2>
                        <p class="text-black">{{ $sale->user->name ?? 'Usuario' }}</p>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800">Cliente:</h2>
                        <p class="text-black">{{ $sale->customer->name ?? 'Cliente General' }}</p>
                        @if($sale->customer && $sale->customer->phone)
                            <p class="text-black">{{ $sale->customer->phone }}</p>
                        @endif
                    </div>
                </div>

                {{-- Tabla de Detalles --}}
                <h2 class="text-xl font-semibold mb-3 text-gray-800">Detalle de la Compra</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-2 px-3 text-black">Producto</th>
                            <th class="text-center py-2 px-3 text-black">Cant.</th>
                            <th class="text-right py-2 px-3 text-black">P. Unit.</th>
                            <th class="text-right py-2 px-3 text-black">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($sale->details as $detail)
                            <tr class="border-b">
                                <td class="py-2 px-3 text-black">{{ $detail->product_name }}</td>
                                <td class="text-center py-2 px-3 text-black">{{ $detail->quantity }}</td>
                                <td class="text-right py-2 px-3 text-black">${{ number_format($detail->unit_price, 2) }}</td>
                                <td class="text-right py-2 px-3 text-black">${{ number_format($detail->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totales --}}
                <div class="mt-6 flex justify-end">
                    <div class="w-full max-w-xs text-sm">
                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium text-gray-700">Subtotal:</span>
                            <span class="font-medium text-black">${{ number_format($sale->subtotal, 2) }}</span>
                        </div>

                        @if($sale->tax_amount > 0)
                            <div class="flex justify-between py-2 border-b">
                                <span class="font-medium text-gray-700">Impuestos:</span>
                                <span class="font-medium text-black">${{ number_format($sale->tax_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between py-2 border-b text-lg font-bold text-black">
                            <span>TOTAL:</span>
                            <span>${{ number_format($sale->total, 2) }}</span>
                        </div>

                        <div class="flex justify-between py-2 mt-2">
                            <span class="font-medium text-gray-700">Método de Pago:</span>
                            <span class="font-medium text-black capitalize">{{ $sale->payment_method }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="font-medium text-gray-700">Pagado:</span>
                            <span class="font-medium text-black">${{ number_format($sale->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="font-medium text-gray-700">Cambio:</span>
                            <span class="font-medium text-black">${{ number_format($sale->change_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pie con mensaje personalizado --}}
                <div class="text-center mt-8 text-gray-500 text-xs">
                    <p>{{ $settings->receipt_message ?? '¡Gracias por su compra!' }}</p>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="max-w-2xl mx-auto mt-6 flex justify-between">
            <a href="{{ route('sales.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300">
                ← Volver al POS
            </a>
            <button onclick="printTicket()"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300">
                🖨️ Imprimir Ticket
            </button>
        </div>
    </div>

    <script>
        function printTicket() {
            var printContents = document.getElementById('ticket-content').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            location.reload();
        }
    </script>
@endsection
