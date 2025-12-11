<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        $filters = request()->validate([
            'email' => 'nullable|email',
            'tipo_servicio' => 'nullable|string',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Cotizacion::query()->orderByDesc('id');

        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }
        if (!empty($filters['tipo_servicio'])) {
            $query->where('tipo_servicio', $filters['tipo_servicio']);
        }
        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }
        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        $limit = $filters['limit'] ?? 50;
        return response()->json($query->limit($limit)->get());
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
            'message' => 'Cotizacion guardada correctamente',
            'data' => $cot
        ], 201);
    }
}
