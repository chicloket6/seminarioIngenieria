<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocionReservacionServicioAdicionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocion_reservacion_servicio_adicional', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('reservacion_id')->nullable();
            $table->unsignedBigInteger('servicio_adicional_id')->nullable();
            $table->unsignedBigInteger('promocion_id')->nullable();

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
