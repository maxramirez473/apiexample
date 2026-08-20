<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        return response()->json(Grupo::withCount('alumnos')->get(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:20'],
            'tp_integrador' => ['required', 'string', 'max:250'],
        ]);

        $grupo = Grupo::create($data);

        return response()->json($grupo, 201);
    }

    public function show(Grupo $grupo)
    {
        return response()->json($grupo->load('alumnos'), 200);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:20'],
            'tp_integrador' => ['sometimes', 'string', 'max:250'],
        ]);

        $grupo->update($data);

        return response()->json($grupo, 200);
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json(null, 204);
    }

    public function alumnos(Grupo $grupo)
    {
        return response()->json($grupo->alumnos()->get(), 200);
    }
}
