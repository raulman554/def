<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsuariosTable extends Migration
{
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('apellido', 100)->after('nombre');
            $table->string('numero_whatsapp', 30)->nullable()->after('email');
            $table->date('fecha_nacimiento')->nullable()->after('numero_whatsapp');
            $table->boolean('perfil_completo')->default(false)->after('saldo_usd');
            $table->string('id_binance')->nullable()->after('perfil_completo');
            $table->text('detalles_cuenta_bancaria')->nullable()->after('id_binance');
            $table->text('datos_pago_oxxo')->nullable()->after('detalles_cuenta_bancaria');
            $table->timestamp('ultima_conexion')->nullable()->after('datos_pago_oxxo');
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'apellido',
                'numero_whatsapp',
                'fecha_nacimiento',
                'perfil_completo',
                'id_binance',
                'detalles_cuenta_bancaria',
                'datos_pago_oxxo',
                'ultima_conexion',
            ]);
        });
    }
}
