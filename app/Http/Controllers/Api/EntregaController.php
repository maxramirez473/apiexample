<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entrega;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EntregaController extends Controller
{
    public function storeWithoutIdempotency(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'entrega' => ['required', 'array'],
            'entrega.nombre' => ['required', 'string', 'max:30'],
            'entrega.fecha_entrega' => ['required', 'date'],
            'archivo' => ['required', 'string', 'max:250'],
            'comentario' => ['required', 'string', 'max:250'],
        ]);

        $entrega = Entrega::firstOrCreate(
            [
                'nombre' => $data['entrega']['nombre'],
                'fecha_entrega' => $data['entrega']['fecha_entrega'],
            ]
        );

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
                'data' => $grupo->entregas()->where('entregas.id', $entrega->id)->first(),
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'La entrega ya existe para este grupo.',
                'error' => 'duplicado_en_pivote',
            ], 409);
        }
    }

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
                'data' => $grupo->entregas()->where('entregas.id', $entrega->id)->first(),
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