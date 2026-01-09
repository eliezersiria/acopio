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
            $table->foreignId('localidad_id')->constrained('localidads')->restrictOnDelete()->cascadeOnUpdate();

            // Fecha y hora de la entrega
            $table->date('fecha');
            $table->time('hora')->nullable();

            // Cantidad entregada
            $table->decimal('litros', 10, 2)->nullable();
            
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
