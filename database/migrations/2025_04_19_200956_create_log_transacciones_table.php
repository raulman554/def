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
    Schema::create('log_transacciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')
              ->constrained('usuarios')
              ->onDelete('cascade');
        $table->enum('tipo', ['deposito', 'retiro', 'premio'])
              ->comment('Tipo de transacción');
        $table->decimal('monto_usd', 10, 2);
        $table->string('descripcion')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('log_transacciones');
}

};
