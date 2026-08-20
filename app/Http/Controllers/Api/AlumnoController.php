<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'API REST Example',
    version: '1.0.0',
    description: 'API para gestionar alumnos, grupos y evaluaciones'
)]
class AlumnoController extends Controller
{
    #[OA\Schema(
        schema: 'Alumno',
        type: 'object',
        required: ['legajo', 'nombres', 'apellidos', 'email', 'grupo_id'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'legajo', type: 'integer', example: 1234),
            new OA\Property(property: 'nombres', type: 'string', example: 'Ana'),
            new OA\Property(property: 'apellidos', type: 'string', example: 'García'),
            new OA\Property(property: 'email', type: 'string', example: 'ana@correo.com'),
            new OA\Property(property: 'grupo_id', type: 'integer', example: 2),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: '/api/alumnos',
        operationId: 'getAlumnos',
        summary: 'Listar alumnos',
        tags: ['Alumnos'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Listado de alumnos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Alumno')
                )
            )
        ]
    )]
    public function list()
    {
        return response()->json(Alumno::with('grupo')->get(), 200);
    }

    #[OA\Post(
        path: '/api/alumnos',
        operationId: 'createAlumno',
        summary: 'Crear alumno',
        tags: ['Alumnos'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['legajo', 'nombres', 'apellidos', 'email', 'grupo_id'],
                properties: [
                    new OA\Property(property: 'legajo', type: 'integer', example: 1234),
                    new OA\Property(property: 'nombres', type: 'string', example: 'Ana'),
                    new OA\Property(property: 'apellidos', type: 'string', example: 'García'),
                    new OA\Property(property: 'email', type: 'string', example: 'ana@correo.com'),
                    new OA\Property(property: 'grupo_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Alumno creado'),
            new OA\Response(response: 422, description: 'Datos inválidos')
        ]
    )]
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

    #[OA\Get(
        path: '/api/alumnos/{id}',
        operationId: 'getAlumnoById',
        summary: 'Obtener alumno por id',
        tags: ['Alumnos'],
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
                description: 'Alumno encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/Alumno')
            ),
            new OA\Response(response: 404, description: 'Alumno no encontrado')
        ]
    )]
    public function show(Alumno $alumno)
    {
        return response()->json($alumno->load('grupo', 'evaluaciones'), 200);
    }

    #[OA\Get(
        path: '/api/alumnos/{id}/evaluaciones',
        operationId: 'getAlumnoEvaluaciones',
        summary: 'Listar evaluaciones del alumno',
        tags: ['Alumnos'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Listado de evaluaciones')
        ]
    )]
    public function evaluaciones(Alumno $alumno)
    {
        return response()->json(
            $alumno->evaluaciones()->withPivot('nota', 'fecha_evaluacion')->get(),
            200
        );
    }

    #[OA\Post(
        path: '/api/alumnos/{id}/evaluaciones',
        operationId: 'associateAlumnoEvaluacion',
        summary: 'Asignar evaluación a un alumno',
        tags: ['Alumnos'],
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
                required: ['evaluacion_id', 'nota', 'fecha_evaluacion'],
                properties: [
                    new OA\Property(property: 'evaluacion_id', type: 'integer', example: 1),
                    new OA\Property(property: 'nota', type: 'number', example: 8),
                    new OA\Property(property: 'fecha_evaluacion', type: 'string', format: 'date', example: '2026-08-20'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Evaluación asociada correctamente'),
            new OA\Response(response: 409, description: 'La relación ya existe'),
            new OA\Response(response: 422, description: 'Payload inválido')
        ]
    )]
    public function addEvaluacion(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'evaluacion_id' => ['required', 'exists:evaluaciones,id'],
            'nota' => ['required', 'numeric', 'between:0,10'],
            'fecha_evaluacion' => ['required', 'date'],
        ]);

        $alumno->evaluaciones()->syncWithoutDetaching([
            $data['evaluacion_id'] => [
                'nota' => $data['nota'],
                'fecha_evaluacion' => $data['fecha_evaluacion'],
            ],
        ]);

        return response()->json([
            'message' => 'Evaluación asociada correctamente.',
        ], 201);
    }
}