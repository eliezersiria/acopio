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
        Schema::create('precios_leche', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio_litro', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // cuando cambia, se cierra
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_leches');
    }
};
