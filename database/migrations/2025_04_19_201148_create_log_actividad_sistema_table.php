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
    Schema::create('log_actividad_sistema', function (Blueprint $table) {
        $table->id();
        $table->string('usuario_tipo');     // 'usuario' o 'administrador'
        $table->unsignedBigInteger('usuario_id')->nullable();
        $table->string('accion');           // Descripción breve de la acción
        $table->text('detalles')->nullable(); // JSON o texto con datos extra
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('log_actividad_sistema');
}

};
