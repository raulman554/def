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
    Schema::create('retiros', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')
              ->constrained('usuarios')
              ->onDelete('cascade');
        $table->decimal('monto_usd', 10, 2);
        $table->enum('estado', ['pendiente', 'completado', 'rechazado'])
              ->default('pendiente');
        $table->string('medio_pago')->nullable(); // datos para el pago (PayPal, cuenta bancaria, etc.)
        $table->text('comentarios')->nullable();  // observaciones o notas del admin
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('retiros');
}

};
