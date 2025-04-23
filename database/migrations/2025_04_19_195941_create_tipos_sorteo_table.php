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
    Schema::create('tipos_sorteo', function (Blueprint $table) {
        $table->id();                                      // id
        $table->string('nombre')->unique();                // nombre
        $table->decimal('precio_ticket_usd',10,2);         // precio_ticket_usd
        $table->integer('max_tickets');                    // max_tickets
        $table->decimal('payout_ratio',5,4);               // payout_ratio
        $table->string('frecuencia_desc');                 // frecuencia_desc
        $table->string('descripcion_breve')->nullable();   // descripci¨®n breve
        $table->boolean('activo')->default(true);          // activo
        $table->timestamps();
    });
}


};
