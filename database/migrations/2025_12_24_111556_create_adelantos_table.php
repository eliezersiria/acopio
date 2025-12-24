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
        Schema::create('adelantos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productor_id');
            $table->decimal('efectivo', 8, 2)->nullable();
            $table->decimal('combustible', 8, 2)->nullable();
            $table->decimal('alimentos', 8, 2)->nullable();
            $table->decimal('lacteos', 8, 2)->nullable();
            $table->decimal('otros', 8, 2)->nullable();
            $table->date('fecha');
            $table->timestamps();

            // Foreign key sin cascada
            $table->foreign('productor_id')
                ->references('id')
                ->on('productors')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adelantos');
    }
};
