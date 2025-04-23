<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTiposSorteoTable extends Migration
{
    public function up()
    {
        Schema::table('tipos_sorteo', function (Blueprint $table) {
            $table->decimal('precio_ticket_usd', 10, 2)->after('nombre');
            $table->integer('max_tickets')->after('precio_ticket_usd');
            $table->decimal('payout_ratio', 5, 4)->after('max_tickets');
            $table->string('frecuencia_desc', 100)->after('payout_ratio');
            $table->text('descripcion_breve')->nullable()->after('frecuencia_desc');
            $table->boolean('activo')->default(true)->after('descripcion_breve');
        });
    }

    public function down()
    {
        Schema::table('tipos_sorteo', function (Blueprint $table) {
            $table->dropColumn([
                'precio_ticket_usd',
                'max_tickets',
                'payout_ratio',
                'frecuencia_desc',
                'descripcion_breve',
                'activo',
            ]);
        });
    }
}
