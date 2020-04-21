<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservacion extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reservaciones';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTotalGastado($number_format = false){
        $total = $this->costo_total;

        return $number_format ? number_format($total, 2) : $total;
    }
    /*
    |--------------------------------------------------------------------------
    | bOTONES
    |--------------------------------------------------------------------------
    */
    public function descargarExcelButton(){
        return '<a href="/admin/reporte/descargar" class="btn btn-xs btn-default"><i class="fa fa-download"></i> Reporte</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function habitacion(){
        return $this->belongsTo('App\Models\Habitacion', 'habitacion_id');
    }
    public function cliente(){
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }
    public function metodoPago(){
        return $this->belongsTo('App\Models\MetodoPago', 'metodo_pago_id');
    }
    public function promocion(){
        return $this->belongsTo('App\Models\Promocion', 'promocion_id');
    }

    public function servicioAdicional(){
        return $this->morphToMany('App\Models\SerivioAdicional', 'promocion_reservacion_servicio_adicional', 'reservacion_id', 'servicio_adicional_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
