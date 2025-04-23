<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('instancia_sorteo_id')
              ->constrained('instancias_sorteo')
              ->onDelete('cascade');
        $table->foreignId('usuario_id')
              ->constrained('usuarios')
              ->onDelete('cascade');
        $table->string('numero');                        // Número o código del ticket
        $table->enum('estado', ['disponible', 'reservado', 'vendido'])
              ->default('disponible');
        $table->dateTime('reservado_hasta')->nullable(); // Fecha y hora de expiración de reserva
        $table->decimal('precio_usd', 10, 2);           // Precio del ticket
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('tickets');
}

};
