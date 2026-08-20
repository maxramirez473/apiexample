<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entrega;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;


class EntregaController extends Controller
{
    #[OA\Post(
        path: '/api/grupos/{grupo}/entregas',
        operationId: 'createEntregaSinIdempotencia',
        summary: 'Crear entrega sin idempotencia',
        tags: ['Entregas'],
        parameters: [
            new OA\Parameter(
                name: 'grupo',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['entrega', 'archivo', 'comentario'],
                properties: [
                    new OA\Property(
                        property: 'entrega',
                        type: 'object',
                        required: ['nombre', 'fecha_entrega'],
                        properties: [
                            new OA\Property(property: 'nombre', type: 'string', example: 'TP 1'),
                            new OA\Property(property: 'fecha_entrega', type: 'string', format: 'date', example: '2026-08-20'),
                        ]
                    ),
                    new OA\Property(property: 'archivo', type: 'string', example: 'archivo.pdf'),
                    new OA\Property(property: 'comentario', type: 'string', example: 'Entrega del grupo'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Entrega asociada correctamente'),
            new OA\Response(response: 409, description: 'Conflicto de datos duplicados'),
            new OA\Response(response: 422, description: 'Payload inválido')
        ]
    )]
    public function storeWithoutIdempotency(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'entrega' => ['required', 'array'],
            'entrega.nombre' => ['required', 'string', 'max:30'],
            'entrega.fecha_entrega' => ['required', 'date'],
            'archivo' => ['required', 'string', 'max:250'],
            'comentario' => ['required', 'string', 'max:250'],
        ]);

        $entrega = Entrega::firstOrCreate([
            'nombre' => $data['entrega']['nombre'],
            'fecha_entrega' => $data['entrega']['fecha_entrega'],
        ]);

        try {
            $grupo->entregas()->syncWithoutDetaching([
                $entrega->id => [
                    'fecha_entrega' => $data['entrega']['fecha_entrega'],
                    'archivo' => $data['archivo'],
                    'comentario' => $data['comentario'],
                ],
            ]);

            return response()->json([
                'message' => 'Entrega asociada al grupo.',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'La entrega ya existe para este grupo.',
                'error' => 'duplicado_en_pivote',
            ], 409);
        }
    }

    #[OA\Post(
        path: '/api/grupos/{grupo}/entregas/idempotent',
        operationId: 'createEntregaConIdempotencia',
        summary: 'Crear entrega con idempotencia',
        tags: ['Entregas'],
        parameters: [
            new OA\Parameter(
                name: 'grupo',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'Idempotency-Key',
                in: 'header',
                required: true,
                description: 'Clave para evitar reenvíos duplicados',
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['entrega', 'archivo', 'comentario'],
                properties: [
                    new OA\Property(
                        property: 'entrega',
                        type: 'object',
                        required: ['nombre', 'fecha_entrega'],
                        properties: [
                            new OA\Property(property: 'nombre', type: 'string', example: 'TP 1'),
                            new OA\Property(property: 'fecha_entrega', type: 'string', format: 'date', example: '2026-08-20'),
                        ]
                    ),
                    new OA\Property(property: 'archivo', type: 'string', example: 'archivo.pdf'),
                    new OA\Property(property: 'comentario', type: 'string', example: 'Entrega del grupo'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Entrega creada con idempotencia'),
            new OA\Response(response: 200, description: 'Reintento repetido con la misma clave'),
            new OA\Response(response: 409, description: 'Conflicto de datos duplicados'),
            new OA\Response(response: 422, description: 'Falta Idempotency-Key o payload inválido')
        ]
    )]
    public function storeWithIdempotency(Request $request, Grupo $grupo)
    {
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return response()->json([
                'message' => 'Falta el header Idempotency-Key para esta operación.',
            ], 422);
        }

        $cacheKey = 'idempotency:grupo_entrega:' . $grupo->id . ':' . $key;

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey), 200);
        }

        $data = $request->validate([
            'entrega' => ['required', 'array'],
            'entrega.nombre' => ['required', 'string', 'max:30'],
            'entrega.fecha_entrega' => ['required', 'date'],
            'archivo' => ['required', 'string', 'max:250'],
            'comentario' => ['required', 'string', 'max:250'],
        ]);

        $entrega = Entrega::firstOrCreate([
            'nombre' => $data['entrega']['nombre'],
            'fecha_entrega' => $data['entrega']['fecha_entrega'],
        ]);

        try {
            $grupo->entregas()->syncWithoutDetaching([
                $entrega->id => [
                    'fecha_entrega' => $data['entrega']['fecha_entrega'],
                    'archivo' => $data['archivo'],
                    'comentario' => $data['comentario'],
                ],
            ]);

            $payload = [
                'message' => 'Entrega asociada con idempotencia.',
            ];

            Cache::put($cacheKey, $payload, now()->addDay());

            return response()->json($payload, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'La entrega ya existe para este grupo.',
                'error' => 'duplicado_en_pivote',
            ], 409);
        }
    }
}