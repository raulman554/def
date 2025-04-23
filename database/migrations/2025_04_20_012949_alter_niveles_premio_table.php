<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('niveles_premio', function (Blueprint $table) {
            // 1) renombra 'nombre' → 'nivel'
            $table->renameColumn('nombre', 'nivel');
            // 2) renombra 'monto' → 'monto_premio_usd'
            $table->renameColumn('monto', 'monto_premio_usd');
            // 3) añade 'cantidad_ganadores' tras 'nivel'
            $table->integer('cantidad_ganadores')
                  ->default(1)
                  ->after('nivel');
        });
    }

    public function down(): void
    {
        Schema::table('niveles_premio', function (Blueprint $table) {
            // 1) eliminar columna nueva
            $table->dropColumn('cantidad_ganadores');
            // 2) renombrar de vuelta
            $table->renameColumn('nivel', 'nombre');
            $table->renameColumn('monto_premio_usd', 'monto');
        });
    }
};
