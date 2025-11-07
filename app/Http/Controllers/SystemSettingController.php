<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings\GeneralSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SystemSettingController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return view('settings.index', [
            'settings' => $settings
        ]);
    }

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

        // Guardar directamente en la base de datos
        DB::table('settings')
            ->where('group', 'general')
            ->where('name', 'general')
            ->update([
                'payload' => json_encode($validated),
                'updated_at' => now(),
            ]);

        // Limpiar la caché de settings
        cache()->forget('settings.general');

        return redirect()->route('settings.index')
            ->with('success', 'Configuraciones actualizadas exitosamente.');
    }
}
