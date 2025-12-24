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
        Schema::create('productors', function (Blueprint $table) {
            $table->id();
            // Relación con comarca (sin cascade)
            // si se elimina la comarca, el productor queda con null
            $table->foreignId('localidad_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            // Información del productor            
            $table->string('nombre', 150);
            $table->string('cedula', 30)->nullable()->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();            
            $table->boolean('activo')->default(true);
            $table->string('semana',3);
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productors');
    }
};
