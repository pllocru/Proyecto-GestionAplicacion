<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClienteAplicacion extends Pivot
{
    protected $table = 'cliente_aplicacion'; // Nombre real de la tabla

    protected $fillable = [
        'cliente_id',
        'aplicacion_id',
        'tipo',
        'cantidad',
        'pagado',
        'version',
        'fecha_contratacion',
        'fecha_expiracion',
        'comentarios',
    ];
}
