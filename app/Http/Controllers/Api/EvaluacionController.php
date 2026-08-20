<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        return response()->json(Evaluacion::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:20'],
            'nota_minima_aprobacion' => ['required', 'integer', 'min:0', 'max:10'],
            'nota_minima_promocion' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $evaluacion = Evaluacion::create($data);

        return response()->json($evaluacion, 201);
    }

    public function show(Evaluacion $evaluacion)
    {
        return response()->json($evaluacion->load('alumnos'), 200);
    }

    public function update(Request $request, Evaluacion $evaluacion)
    {
        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:20'],
            'nota_minima_aprobacion' => ['sometimes', 'integer', 'min:0', 'max:10'],
            'nota_minima_promocion' => ['sometimes', 'integer', 'min:0', 'max:10'],
        ]);

        $evaluacion->update($data);

        return response()->json($evaluacion, 200);
    }

    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();

        return response()->json(null, 204);
    }

    public function alumnos(Evaluacion $evaluacion)
    {
        return response()->json(
            $evaluacion->alumnos()->withPivot('nota', 'fecha_evaluacion')->get(),
            200
        );
    }
}