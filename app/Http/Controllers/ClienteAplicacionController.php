<?php

namespace App\Http\Controllers;
use App\Models\Aplicacion;
use App\Models\Cliente;
use App\Models\Cooperativa;
use Illuminate\Http\Request;

class ClienteAplicacionController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['cooperativa', 'aplicaciones'])->paginate(10);
        $cooperativas = Cooperativa::all();
        $aplicaciones = Aplicacion::all();


        return view('cliente.index', compact('clientes', 'cooperativas', 'aplicaciones'));
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,cliente_id',
            'aplicaciones' => 'nullable|array',
            'aplicaciones.*.aplicacion_id' => 'required|exists:aplicacion,aplicacion_id',
            'aplicaciones.*.tipo' => 'nullable|string|max:50',
            'aplicaciones.*.version' => 'nullable|string|max:50',
            'aplicaciones.*.fecha_contratacion' => 'nullable|date',
        ]);


        $cliente = Cliente::findOrFail($validated['cliente_id']);


        if (!empty($validated['aplicaciones'])) {
            foreach ($validated['aplicaciones'] as $aplicacion) {
                $cliente->aplicaciones()->syncWithoutDetaching([
                    $aplicacion['aplicacion_id'] => [
                        'tipo' => $aplicacion['tipo'] ?? null,
                        'version' => $aplicacion['version'] ?? null,
                        'fecha_contratacion' => $aplicacion['fecha_contratacion'] ?? null,
                    ]
                ]);

            }
        }

        return redirect()->route('clientes.index')->with('success', 'Aplicaciones asociadas al cliente exitosamente.');
    }

    // ClienteController.php
    public function ajaxList()
    {
        $clientes = Cliente::with(['cooperativa', 'aplicaciones'])->get();

        return response()->json($clientes);
    }





}
