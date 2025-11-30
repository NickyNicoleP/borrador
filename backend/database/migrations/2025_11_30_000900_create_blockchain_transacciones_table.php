<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blockchain_transacciones', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor')->nullable(); // ej. 'alchemy', 'infura'
            $table->string('red', 50)->nullable(); // ej. 'ethereum', 'polygon'
            $table->string('tx_hash')->unique();
            $table->string('wallet_origen')->nullable();
            $table->string('wallet_destino')->nullable();
            $table->decimal('monto', 18, 8)->nullable();
            $table->string('moneda', 20)->default('USDT');
            $table->enum('estado', ['pendiente', 'confirmada', 'fallida'])->default('pendiente');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blockchain_transacciones');
    }
};
