<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        return response()->json(Cotizacion::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_servicio' => 'required',
            'datos_gb' => 'required|integer',
            'minutos' => 'required|integer',
            'sms' => 'required|integer',
            'precio_final' => 'required|numeric',
            'plan_recomendado' => 'required|string',
            'email' => 'nullable|string',
            'telefono' => 'nullable|string'
        ]);

        $cot = Cotizacion::create($data);

        return response()->json([
            'message' => 'Cotización guardada correctamente',
            'data' => $cot
        ], 201);
    }
}
