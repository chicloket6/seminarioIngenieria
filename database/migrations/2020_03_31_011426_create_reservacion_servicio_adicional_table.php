<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservacionServicioAdicionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservacion_servicio_adicional', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('reservacion_id')->nullable();
            $table->foreign('reservacion_id')->references('id')->on('reservaciones');

            $table->unsignedBigInteger('servicio_adicional_id')->nullable();
            $table->foreign('servicio_adicional_id')->references('id')->on('servicios_adicional');

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
        Schema::dropIfExists('promocion_reservacion_servicio_adicional');
    }
}
