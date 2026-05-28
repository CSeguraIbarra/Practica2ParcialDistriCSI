<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  response(200)->header('Content-Type', 'application/json')->setContent(json_encode(Persona::all()));
        
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validar = $request->validate([
        //     'nombres' => 'required|string|max:100',
        //     'apellidos' => 'required|string|max:100',
        //     'documento_identidad' => 'required|string|max:15|unique:personas',
        //     'sexo' => 'required|in:Masculino,Femenino',
        //     'fecha_nacimiento' => 'required|date',
        //     'celular' => 'required|integer|between:10000000,99999999',
        // ]);
        // if (!$validar) {
        //     return response(400)->header('Content-Type', 'application/json')->setContent(json_encode(['error' => 'Datos inválidos']));
        // }
        $persona = Persona::create($request->all());
        return response(201)->header('Content-Type', 'application/json')->setContent(json_encode($persona));           
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        
        // return response(200)->header('Content-Type', 'application/json')->setContent(json_encode($persona));
        return $persona;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        // $validar = $request->validate([
        //     'nombres' => 'sometimes|required|string|max:100',
        //     'apellidos' => 'sometimes|required|string|max:100',          
        //     'documento_identidad' => 'sometimes|required|string|max:15|unique:personas,documento_identidad,' . $persona->id,
        //     'sexo' => 'sometimes|required|in:Masculino,Femenino,      
        //     'fecha_nacimiento' => 'sometimes|required|date',
        //     'celular' => 'sometimes|required|integer|between:10000000,99999999',
        // ]);
        // if (!$validar) {
        //     return response(400)->header('Content-Type', 'application/json')->setContent
        //     (json_encode(['error' => 'Datos inválidos']));
        // }
        $persona->update($request->all());
        return response(200)->header('Content-Type', 'application/json')->setContent(json_encode($persona));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        
        $persona->delete();
        return response(204)->header('Content-Type', 'application/json')->setContent(json_encode($persona));
    }
    
}
