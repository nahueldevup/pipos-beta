<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Configuración del Sistema</h1>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                {{-- Mensaje de éxito --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Ups!</strong>
                        <span class="block sm:inline">Hay algunos problemas con los datos:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario --}}
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Columna 1: Información de la Empresa --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Información de la Empresa</h3>

                            <div class="mb-4">
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre de la Empresa <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="company_name"
                                       name="company_name"
                                       value="{{ old('company_name', $settings->company_name) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Dirección
                                </label>
                                <input type="text"
                                       id="company_address"
                                       name="company_address"
                                       value="{{ old('company_address', $settings->company_address) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Teléfono
                                </label>
                                <input type="text"
                                       id="company_phone"
                                       name="company_phone"
                                       value="{{ old('company_phone', $settings->company_phone) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="receipt_message" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mensaje del Ticket
                                </label>
                                <textarea id="receipt_message"
                                          name="receipt_message"
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('receipt_message', $settings->receipt_message) }}</textarea>
                            </div>
                        </div>

                        {{-- Columna 2: Configuración del POS --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700">Configuración del POS</h3>

                            <div class="mb-4">
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tasa de Impuesto (%) <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       step="0.01"
                                       id="tax_rate"
                                       name="tax_rate"
                                       value="{{ old('tax_rate', $settings->tax_rate) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="low_stock_alert" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alerta de Stock Mínimo (unidades) <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="low_stock_alert"
                                       name="low_stock_alert"
                                       value="{{ old('low_stock_alert', $settings->low_stock_alert) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tema de la Aplicación <span class="text-red-500">*</span>
                                </label>
                                <select id="theme"
                                        name="theme"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="light" @selected(old('theme', $settings->theme) == 'light')>Claro</option>
                                    <option value="dark" @selected(old('theme', $settings->theme) == 'dark')>Oscuro</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- Botón de Guardar --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-300">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
