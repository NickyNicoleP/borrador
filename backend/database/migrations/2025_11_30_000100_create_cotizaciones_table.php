<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_servicio', ['prepago', 'pospago']);
            $table->integer('datos_gb');
            $table->integer('minutos');
            $table->integer('sms');
            $table->decimal('precio_final', 10, 2);
            $table->string('plan_recomendado');
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
