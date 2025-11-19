<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SystemSettingController extends Controller
{
    /**
     * Muestra el formulario de configuración
     */
    public function index()
    {
        $settings = SystemSetting::getSettings();

        return view('settings.index', [
            'settings' => $settings
        ]);
    }

    /**
     * Actualiza las configuraciones del sistema
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'receipt_message' => 'nullable|string',
            'low_stock_alert' => 'required|integer|min:0',
            'theme' => 'required|in:light,dark',
        ]);

        // Convertir campos vacíos a string vacío en lugar de null
        $validated['company_address'] = $validated['company_address'] ?? '';
        $validated['company_phone'] = $validated['company_phone'] ?? '';
        $validated['receipt_message'] = $validated['receipt_message'] ?? '';

        // Convertir tax_rate a float
        $validated['tax_rate'] = (float) $validated['tax_rate'];

        // Guardar las configuraciones usando el modelo
        SystemSetting::updateSettings($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Configuraciones actualizadas exitosamente.');
    }
}
