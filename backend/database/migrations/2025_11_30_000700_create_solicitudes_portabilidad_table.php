<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitudes_portabilidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente');
            $table->string('email');
            $table->string('telefono', 20)->nullable();
            $table->string('compania_origen')->nullable();
            $table->string('numero_a_portar', 20);
            $table->foreignId('plan_id')->nullable()->constrained('planes')->nullOnDelete();
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'rechazada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_portabilidad');
    }
};
