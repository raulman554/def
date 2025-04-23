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
        Schema::create('instancias_sorteo', function (Blueprint $table) {
            $table->id('id_instancia_sorteo');
            $table->foreignId('id_tipo_sorteo')
                  ->constrained('tipos_sorteo','id_tipo_sorteo')
                  ->onDelete('restrict');
            $table->timestamp('fecha_hora_sorteo');
            $table->timestamp('fecha_cierre_ventas');
            $table->text('info_loterias_oficiales')->nullable();
            $table->text('numeros_ganadores_oficiales')->nullable();
            $table->enum('estado',['Programado','Abierto','Cerrado','Procesando','Finalizado'])
                  ->default('Programado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instancias_sorteo');
    }
};
