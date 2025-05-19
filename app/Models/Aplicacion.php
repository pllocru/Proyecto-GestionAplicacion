<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicacion extends Model
{
    use HasFactory;

    protected $table = 'aplicacion'; // nombre real de la tabla
    protected $primaryKey = 'aplicacion_id'; // <- esta línea es la clave de todo
    public $timestamps = false;


    protected $fillable = [
        'nombre',
        'descripcion',
        'datos_contrato',
        'tipo_contrato',
        'fecha_lanzamiento',
        'estado',
    ];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_aplicacion', 'aplicacion_id', 'cliente_id')
            ->withPivot('tipo', 'cantidad', 'pagado', 'version', 'fecha_contratacion', 'fecha_expiracion', 'comentarios');
    }
}
