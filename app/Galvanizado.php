<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Galvanizado extends Model {

    public $timestamps = false;
    protected $table = 'tab_galv';
    protected $primaryKey = 'intIdGalva';
    protected $fillable = [
        'intIdUniNego',
        'varTipoOrden',
        'intIdProy',
        'intIdTipoProducto',
        'varRazoSoci',
        'varOrdenServi',
        'varDescripcion',
        'dateFechIngr',
        'dateFechSali',
        'varNumeGuia',
        'intCantTota',
        'intCantRegi',
        'deciPesoInge',
        'deciPesoBruto',
        'deciPesoNegro',
        'deciPesoGalv',
        'deciConsumoZinc',
        'varPorcZinc',
        'intIdEsta',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi'
    ];

}
