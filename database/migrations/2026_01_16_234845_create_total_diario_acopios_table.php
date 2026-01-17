<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('total_diario_acopios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('localidad_id')->constrained();
            $table->date('fecha');
            $table->enum('tipo_semana', ['A', 'B']);
            $table->integer('litros')->default(0);
            $table->unique(['fecha', 'localidad_id', 'tipo_semana']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_diario_acopios');
    }
};
