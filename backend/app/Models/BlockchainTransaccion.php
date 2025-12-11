<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockchainTransaccion extends Model
{
    protected $table = 'blockchain_transacciones';

    protected $fillable = [
        'proveedor',
        'red',
        'tx_hash',
        'wallet_origen',
        'wallet_destino',
        'monto',
        'moneda',
        'estado',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
