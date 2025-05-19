<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class Cooperativa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo_electronico',
        'fecha_fundacion'
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
