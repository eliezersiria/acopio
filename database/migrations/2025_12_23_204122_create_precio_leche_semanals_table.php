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
        Schema::create('precio_leche_semanals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productor_id');
            $table->unsignedBigInteger('localidad_id');
            $table->decimal('precio', 8, 2);
            $table->date('fecha_inicio');
            $table->timestamps();
            // Foreign key sin cascada
            $table->foreign('productor_id')->references('id')->on('productors')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('localidad_id')->references('id')->on('localidads')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_leche_semanals');
    }
};
