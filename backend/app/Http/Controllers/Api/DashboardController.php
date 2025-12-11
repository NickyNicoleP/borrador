<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Cotizacion;
use App\Models\SolicitudPortabilidad;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $filters = $request->validate([
            'email' => 'nullable|email',
            'usuario_id' => 'nullable|integer',
        ]);

        $email = $filters['email'] ?? null;
        $usuarioId = $filters['usuario_id'] ?? null;

        $compraQuery = Compra::query();
        if ($email) {
            $compraQuery->where('email_contacto', $email);
        }
        if ($usuarioId) {
            $compraQuery->where('usuario_id', $usuarioId);
        }

        $saldoDisponible = (clone $compraQuery)->where('estado', 'pagado')->sum('monto');
        $montoPendiente = (clone $compraQuery)->where('estado', 'pendiente')->sum('monto');
        $comprasRecientes = (clone $compraQuery)->orderByDesc('created_at')->limit(5)->get();

        $portabilidadesQuery = SolicitudPortabilidad::query();
        if ($email) {
            $portabilidadesQuery->where('email', $email);
        }
        $portabilidades = $portabilidadesQuery->orderByDesc('created_at')->limit(5)->get();

        $cotizacionesQuery = Cotizacion::query();
        if ($email) {
            $cotizacionesQuery->where('email', $email);
        }
        $cotizaciones = $cotizacionesQuery->orderByDesc('created_at')->limit(5)->get();

        return response()->json([
            'saldo' => [
                'disponible' => (float) $saldoDisponible,
                'pendiente' => (float) $montoPendiente,
            ],
            'compras_recientes' => $comprasRecientes,
            'portabilidades' => $portabilidades,
            'cotizaciones' => $cotizaciones,
        ]);
    }
}
