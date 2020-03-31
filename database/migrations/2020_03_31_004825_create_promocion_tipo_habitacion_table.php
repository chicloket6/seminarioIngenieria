<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocionTipoHabitacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocion_tipo_habitacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('promocion_id')->nullable();
            $table->foreign('promocion_id')->references('id')->on('promociones');
            $table->unsignedBigInteger('tipo_habitacion_id')->nullable();
            $table->foreign('tipo_habitacion_id')->references('id')->on('tipos_habitacion');
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
        Schema::dropIfExists('promocion_tipo_habitacion');
    }
}
