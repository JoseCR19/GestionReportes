<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Grupos extends Model {

    public $timestamps = false;
    protected $table = 'proyecto_paquetes';
    protected $primaryKey = 'intIdProyPaquete';
    protected $fillable = [
        'intIdProyTarea',
        'intIdProy',
        'intIdTipoProducto',
        'varCodigoPaquete',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
        'intIdArmadores',
        'intIdEtapa',
        'intIdContr',
        'fecha_Inicio',
        'fecha_Fin',
        'fecha_TerminoReal',
         'intIdEsta'
    ];

}
