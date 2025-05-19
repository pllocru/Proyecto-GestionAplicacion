<?php


namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        // Obtener los clientes de la base de datos
        $clientes = Cliente::all();
        return response()->json($clientes); // Devolvemos los clientes en formato JSON
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'nif' => 'required|string|max:20|unique:cliente,nif',
            'correo_electronico' => 'required|email|unique:cliente,correo_electronico',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'cooperativa_id' => 'required|exists:cooperativas,id',

        ]);

        // Crear un nuevo cliente en la base de datos
        $cliente = Cliente::create($validatedData);

        return response()->json($cliente, 201);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nif' => 'required|string|max:255|unique:clientes,nif,' . $cliente->id,
            'correo_electronico' => 'required|email|unique:clientes,correo_electronico,' . $cliente->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'cooperativa_id' => 'required|exists:cooperativas,id'
        ]);

        $cliente->update($validated);
        return response()->json($cliente);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(null, 204);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            return response()->json($cliente);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }



}
