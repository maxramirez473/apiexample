<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class EvaluacionController extends Controller
{
    #[OA\Schema(
        schema: 'Evaluacion',
        type: 'object',
        required: ['nombre', 'nota_minima_aprobacion', 'nota_minima_promocion'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'nombre', type: 'string', example: 'Parcial 1'),
            new OA\Property(property: 'nota_minima_aprobacion', type: 'integer', example: 4),
            new OA\Property(property: 'nota_minima_promocion', type: 'integer', example: 7),
        ]
    )]

    #[OA\Get(
        path: '/api/evaluaciones',
        operationId: 'getEvaluaciones',
        summary: 'Listar evaluaciones',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Listado de evaluaciones',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Evaluacion')
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(Evaluacion::all(), 200);
    }

    #[OA\Post(
        path: '/api/evaluaciones',
        operationId: 'createEvaluacion',
        summary: 'Crear evaluacion',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nombre', 'nota_minima_aprobacion', 'nota_minima_promocion'],
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Parcial 1'),
                    new OA\Property(property: 'nota_minima_aprobacion', type: 'integer', example: 4),
                    new OA\Property(property: 'nota_minima_promocion', type: 'integer', example: 7),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Evaluacion creada'),
            new OA\Response(response: 422, description: 'Datos inválidos')
        ]
    )]
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

    #[OA\Get(
        path: '/api/evaluaciones/{id}',
        operationId: 'getEvaluacionById',
        summary: 'Obtener evaluacion por id',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Evaluacion encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Evaluacion')
            ),
            new OA\Response(response: 404, description: 'Evaluacion no encontrada')
        ]
    )]
    public function show(Evaluacion $evaluacion)
    {
        return response()->json($evaluacion->load('alumnos'), 200);
    }

    #[OA\Put(
        path: '/api/evaluaciones/{id}',
        operationId: 'updateEvaluacion',
        summary: 'Actualizar evaluacion',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Parcial 1'),
                    new OA\Property(property: 'nota_minima_aprobacion', type: 'integer', example: 4),
                    new OA\Property(property: 'nota_minima_promocion', type: 'integer', example: 7),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Evaluacion actualizada'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
            new OA\Response(response: 404, description: 'Evaluacion no encontrada')
        ]
    )]
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

    #[OA\Delete(
        path: '/api/evaluaciones/{id}',
        operationId: 'deleteEvaluacion',
        summary: 'Eliminar evaluacion',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Evaluacion eliminada'),
            new OA\Response(response: 404, description: 'Evaluacion no encontrada')
        ]
    )]
    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();

        return response()->json(null, 204);
    }

    #[OA\Get(
        path: '/api/evaluaciones/{id}/alumnos',
        operationId: 'getAlumnosByEvaluacion',
        summary: 'Obtener alumnos de una evaluacion',
        tags: ['Evaluaciones'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Alumnos obtenidos'),
            new OA\Response(response: 404, description: 'Evaluacion no encontrada')
        ]
    )]
    public function alumnos(Evaluacion $evaluacion)
    {
        return response()->json(
            $evaluacion->alumnos()->withPivot('nota', 'fecha_evaluacion')->get(),
            200
        );
    }
}