<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DetaGalva extends Model {

    public $timestamps = false;
    protected $table = 'deta_galv';
    protected $primaryKey = 'intIdDetaGalv';
    protected $fillable = [
        'intIdPeriValo',
        'intGanchera',
        'varTurno',
        'varHoraEntr',
        'varHoraSali',
        'intCantidad',
        'varTipoMate',
        'deciPesoNegro',
        'deciPesoGalv',
        'deciConsumoZinc',
        'varPorcZinc',
        'dateFechInic',
        'dateFechSali',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
        'intIdEsta',
    ];

}
