<?php

namespace App\Http\Controllers;


use App\Models\Aplicacion;
use Illuminate\Http\Request;


class AplicacionController extends Controller
{
    public function index()
    {
        $aplicaciones = Aplicacion::all();
        return response()->json($aplicaciones);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'datos_contrato' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'fecha_lanzamiento' => 'nullable|date',
            'estado' => 'nullable|string',
        ]);

        Aplicacion::create($validated);

        return response()->json(['message' => 'Aplicación creada con éxito'], 201);
    }

}