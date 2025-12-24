<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acopios', function (Blueprint $table) {
            $table->id();
            // Relación con productor
            $table->foreignId('productor_id')->constrained('productors')->restrictOnDelete()->cascadeOnUpdate();           

            // Fecha y hora de la entrega
            $table->date('fecha');
            $table->time('hora')->nullable();

            // Cantidad entregada
            $table->decimal('litros', 10, 2);

            // Precio por litro en el día de la entrega
            $table->decimal('precio_litro', 10, 2);

            // Total calculado del pago
            $table->decimal('total_pagado', 12, 2);
            $table->text('observaciones')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acopios');
    }
};
