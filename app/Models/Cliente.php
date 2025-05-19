<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';

    protected $primaryKey = 'cliente_id';


    protected $fillable = [
        'nombre',
        'nif',
        'correo_electronico',
        'telefono',
        'cooperativa_id',
    ];

    public function cooperativa()
    {
        return $this->belongsTo(Cooperativa::class);
    }

    public function aplicaciones()
{
    return $this->belongsToMany(Aplicacion::class, 'cliente_aplicacion', 'cliente_id', 'aplicacion_id')
        ->withPivot('tipo', 'cantidad', 'pagado', 'version', 'fecha_contratacion', 'fecha_expiracion', 'comentarios');
}

}
