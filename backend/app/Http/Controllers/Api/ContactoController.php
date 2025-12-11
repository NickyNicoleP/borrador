<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:50',
            'asunto' => 'nullable|string',
            'mensaje' => 'nullable|string',
            'origen' => 'nullable|string|max:100',
            'estado' => 'nullable|in:nuevo,contactado,cerrado',
        ]);

        $contacto = Contacto::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'asunto' => $data['asunto'] ?? null,
            'mensaje' => $data['mensaje'] ?? null,
            'origen' => $data['origen'] ?? null,
            'estado' => $data['estado'] ?? 'nuevo',
        ]);

        return response()->json([
            'message' => 'Contacto registrado',
            'data' => $contacto,
        ], 201);
    }
}
