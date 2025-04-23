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
    Schema::create('niveles_premio', function (Blueprint $table) {
        $table->id();                                         // id
        $table->foreignId('tipo_sorteo_id')                   // FK al tipo
              ->constrained('tipos_sorteo')
              ->onDelete('cascade');
        $table->integer('nivel');                             // nivel
        $table->integer('cantidad_ganadores');                // cantidad_ganadores
        $table->decimal('monto_premio_usd',12,2);             // monto_premio_usd
        $table->timestamps();

        $table->unique(['tipo_sorteo_id','nivel']);           // restricción por tipo+nivel
    });
}



};
