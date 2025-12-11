<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminController extends Controller
{
    public function index()
    {
        return response()->json(Usuario::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,usuario',
            'activo' => 'nullable|boolean',
        ]);

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'activo' => $data['activo'] ?? true,
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => $usuario,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:6',
            'rol' => 'sometimes|required|in:admin,usuario',
            'activo' => 'nullable|boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario,
        ]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente',
        ]);
    }
}
