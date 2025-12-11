<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlockchainTransaccion;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'email' => 'nullable|email',
            'usuario_id' => 'nullable|integer',
            'estado' => 'nullable|in:pendiente,pagado,fallido,reembolsado',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Compra::query()->orderByDesc('created_at');

        if (!empty($filters['email'])) {
            $query->where('email_contacto', $filters['email']);
        }

        if (!empty($filters['usuario_id'])) {
            $query->where('usuario_id', $filters['usuario_id']);
        }

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        $limit = $filters['limit'] ?? 20;

        return response()->json($query->limit($limit)->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id' => 'nullable|exists:usuarios,id',
            'plan_id' => 'required|exists:planes,id',
            'promocion_id' => 'nullable|exists:promociones,id',
            'email_contacto' => 'required|email',
            'telefono' => 'nullable|string|max:50',
            'monto' => 'required|numeric',
            'moneda' => 'nullable|string|max:10',
            'estado' => 'nullable|in:pendiente,pagado,fallido,reembolsado',
            'notas' => 'nullable|string',
            'blockchain' => 'nullable|array',
            'blockchain.tx_hash' => 'required_with:blockchain|string',
            'blockchain.red' => 'nullable|string|max:50',
            'blockchain.proveedor' => 'nullable|string|max:100',
            'blockchain.wallet_origen' => 'nullable|string|max:255',
            'blockchain.wallet_destino' => 'nullable|string|max:255',
            'blockchain.monto' => 'nullable|numeric',
            'blockchain.moneda' => 'nullable|string|max:20',
            'blockchain.estado' => 'nullable|in:pendiente,confirmada,fallida',
            'blockchain.metadata' => 'nullable|array',
        ]);

        $compra = DB::transaction(function () use ($data) {
            $blockchainId = null;
            if (!empty($data['blockchain']['tx_hash'] ?? null)) {
                $blockchain = BlockchainTransaccion::create([
                    'proveedor' => $data['blockchain']['proveedor'] ?? null,
                    'red' => $data['blockchain']['red'] ?? null,
                    'tx_hash' => $data['blockchain']['tx_hash'],
                    'wallet_origen' => $data['blockchain']['wallet_origen'] ?? null,
                    'wallet_destino' => $data['blockchain']['wallet_destino'] ?? null,
                    'monto' => $data['blockchain']['monto'] ?? null,
                    'moneda' => $data['blockchain']['moneda'] ?? 'USDT',
                    'estado' => $data['blockchain']['estado'] ?? 'pendiente',
                    'metadata' => $data['blockchain']['metadata'] ?? null,
                ]);
                $blockchainId = $blockchain->id;
            }

            return Compra::create([
                'usuario_id' => $data['usuario_id'] ?? null,
                'plan_id' => $data['plan_id'],
                'promocion_id' => $data['promocion_id'] ?? null,
                'blockchain_transaccion_id' => $blockchainId,
                'email_contacto' => $data['email_contacto'],
                'telefono' => $data['telefono'] ?? null,
                'monto' => $data['monto'],
                'moneda' => $data['moneda'] ?? 'USD',
                'estado' => $data['estado'] ?? 'pendiente',
                'notas' => $data['notas'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Compra registrada exitosamente',
            'data' => $compra,
        ], 201);
    }
}
