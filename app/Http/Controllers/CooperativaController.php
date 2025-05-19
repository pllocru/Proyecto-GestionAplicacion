<?php

namespace App\Http\Controllers;


use App\Models\Cooperativa;
use Illuminate\Http\Request;

class CooperativaController extends Controller
{

    public function index()
    {
        $cooperativas = Cooperativa::all();
        return response()->json($cooperativas); // Devolvemos las cooperativas en formato JSON
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:cooperativas,nombre',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo_electronico' => 'required|email|unique:cooperativas,correo_electronico',
            'fecha_fundacion' => 'nullable|date',
        ]);
        
        Cooperativa::create($validated);
        
        return response()->json(['message' => 'Cooperativa creada con éxito'], 201);
        
    }


}
;