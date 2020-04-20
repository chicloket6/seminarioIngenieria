<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida');
            $table->boolean('status_reservacion')->default(1);
            $table->double('costo_total', 10, 2);

            $table->unsignedBigInteger('habitacion_id')->nullable();
            $table->foreign('habitacion_id')->references('id')->on('habitaciones');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedBigInteger('metodo_pago_id')->nullable();
            $table->foreign('metodo_pago_id')->references('id')->on('metodos_pago');

            $table->unsignedBigInteger('promocion_id')->nullable();
            $table->foreign('promocion_id')->references('id')->on('promociones');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservaciones');
    }
}
