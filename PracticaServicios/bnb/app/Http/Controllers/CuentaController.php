<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    public function index()
    {
        $cuentas = Cuenta::all();
        return response()->json($cuentas);
    }

    public function store(Request $request)
    {
        $cuenta = Cuenta::create($request->all());
        return response()->json($cuenta, 201);
    }

    public function show($cuenta)
    {
        $c = Cuenta::where('cuenta', $cuenta)->first();
        if (!$c) {
            return response()->json(['error' => 'Cuenta no encontrada'], 404);
        }
        return response()->json($c);
    }

    public function update(Request $request, $cuenta)
    {
        $c = Cuenta::where('cuenta', $cuenta)->first();
        if (!$c) {
            return response()->json(['error' => 'Cuenta no encontrada'], 404);
        }

        $input = $request->all();
        $c->update($input);
        return response()->json($c, 200);
    }

    public function destroy($cuenta)
    {
        $c = Cuenta::where('cuenta', $cuenta)->first();
        if ($c) {
            $c->delete();
        }
        return response()->json(null, 204);
    }
}
