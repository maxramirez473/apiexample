<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AlumnoController extends Controller
{
    public function index()
    {
        return response()->json(Alumno::with('grupo')->get(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'legajo' => ['required', 'integer'],
            'nombres' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50'],
            'grupo_id' => ['required', 'exists:grupos,id'],
        ]);

        $alumno = Alumno::create($data);

        return response()->json($alumno, 201);
    }

    public function show(Alumno $alumno)
    {
        return response()->json($alumno->load('grupo', 'evaluaciones'), 200);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'legajo' => ['sometimes', 'integer'],
            'nombres' => ['sometimes', 'string', 'max:50'],
            'apellidos' => ['sometimes', 'string', 'max:50'],
            'email' => ['sometimes', 'email', 'max:50'],
            'grupo_id' => ['sometimes', 'exists:grupos,id'],
        ]);

        $alumno->update($data);

        return response()->json($alumno, 200);
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();

        return response()->json(null, 204);
    }

    public function evaluaciones(Alumno $alumno)
    {
        return response()->json(
            $alumno->evaluaciones()->withPivot('nota', 'fecha_evaluacion')->get(),
            200
        );
    }

    public function addEvaluacion(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'evaluacion_id' => ['required', 'exists:evaluaciones,id'],
            'nota' => ['required', 'numeric', 'between:0,10'],
            'fecha_evaluacion' => ['required', 'date'],
        ]);

        try {
            $alumno->evaluaciones()->syncWithoutDetaching([
                $data['evaluacion_id'] => [
                    'nota' => $data['nota'],
                    'fecha_evaluacion' => $data['fecha_evaluacion'],
                ],
            ]);

            $evaluacion = $alumno->evaluaciones()
                ->where('evaluaciones.id', $data['evaluacion_id'])
                ->withPivot('nota', 'fecha_evaluacion')
                ->first();

            return response()->json([
                'message' => 'Evaluación asociada correctamente.',
                'data' => $evaluacion,
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'La evaluación ya está asociada a este alumno.',
            ], 409);
        }
    }
}