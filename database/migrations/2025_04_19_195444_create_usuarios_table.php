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
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('email')->unique();
        $table->string('password');
        $table->decimal('saldo_usd', 10, 2)->default(0);  // Saldo inicial
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('usuarios');
}

};
