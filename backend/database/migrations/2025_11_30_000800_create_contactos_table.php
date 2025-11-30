<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono', 20)->nullable();
            $table->string('asunto')->nullable();
            $table->text('mensaje')->nullable();
            $table->string('origen', 100)->nullable();
            $table->enum('estado', ['nuevo', 'contactado', 'cerrado'])->default('nuevo');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
