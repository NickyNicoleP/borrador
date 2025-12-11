<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class CotizacionAdminController extends Controller
{
    public function update(Request $request, $id)
    {
        $cot = Cotizacion::findOrFail($id);

        $data = $request->validate([
            'tipo_servicio' => 'sometimes|string',
            'datos_gb' => 'sometimes|integer|nullable',
            'minutos' => 'sometimes|integer|nullable',
            'sms' => 'sometimes|integer|nullable',
            'precio_final' => 'sometimes|numeric',
            'plan_recomendado' => 'sometimes|string|nullable',
            'email' => 'sometimes|email|nullable',
            'telefono' => 'sometimes|string|nullable',
        ]);

        $cot->update($data);

        return response()->json([
            'message' => 'Cotizacion actualizada correctamente',
            'data' => $cot,
        ]);
    }

    public function destroy($id)
    {
        $cot = Cotizacion::findOrFail($id);
        $cot->delete();

        return response()->json([
            'message' => 'Cotizacion eliminada correctamente'
        ]);
    }
}
