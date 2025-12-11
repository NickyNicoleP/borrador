<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPortabilidad;
use Illuminate\Http\Request;

class SolicitudPortabilidadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_cliente' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:50',
            'compania_origen' => 'nullable|string',
            'numero_a_portar' => 'required|string',
            'plan_id' => 'nullable|exists:planes,id',
            'estado' => 'nullable|in:pendiente,en_proceso,completada,rechazada',
            'notas' => 'nullable|string',
        ]);

        $solicitud = SolicitudPortabilidad::create([
            'nombre_cliente' => $data['nombre_cliente'],
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'compania_origen' => $data['compania_origen'] ?? null,
            'numero_a_portar' => $data['numero_a_portar'],
            'plan_id' => $data['plan_id'] ?? null,
            'estado' => $data['estado'] ?? 'pendiente',
            'notas' => $data['notas'] ?? null,
        ]);

        return response()->json([
            'message' => 'Solicitud de portabilidad registrada',
            'data' => $solicitud,
        ], 201);
    }
}
