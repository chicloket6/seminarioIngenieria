<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

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
    protected $casts = [
        'fecha_entrada' => 'datetime:Y-m-d H:i:s',
        'fecha_salida' => 'datetime:Y-m-d H:i:s',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTotalGastado($number_format = false){
        $total = $this->costo_total;

        foreach($this->serviciosAdicionales as $sa){
            $total += $sa->costo;
        }

        return $number_format ? number_format($total, 2) : $total;
    }
    /*
    |--------------------------------------------------------------------------
    | BOTONES
    |--------------------------------------------------------------------------
    */
    public function descargarExcelButton(){
        return '<a href="/admin/reporte/descargar" class="btn btn-xs btn-default"><i class="fa fa-download"></i> Reporte</a>';
    }

    public function editarButton(){
        $fecha_entrada = new Carbon($this->fecha_entrada);
        $fecha_salida = new Carbon($this->fecha_salida);
        
        if($fecha_entrada < Carbon::now()){
            return '<a href="/admin/reservacion/' . $this->id . '/edit" class="btn btn-sm btn-link"><i class="fa fa-edit"></i> Editar</a>';
        }
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

    public function serviciosAdicionales(){
        return $this->belongsToMany('App\Models\ServicioAdicional', 'reservacion_servicio_adicional', 'reservacion_id', 'servicio_adicional_id');
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
