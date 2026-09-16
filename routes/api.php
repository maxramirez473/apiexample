<?php

use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\EvaluacionController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\EntregaController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('grupos', GrupoController::class);
    Route::get('grupos/{grupo}/alumnos', [GrupoController::class, 'alumnos']);

    Route::apiResource('alumnos', AlumnoController::class);
    Route::get('alumnos/{alumno}/evaluaciones', [AlumnoController::class, 'evaluaciones']);
    Route::post('alumnos/{alumno}/evaluaciones', [AlumnoController::class, 'addEvaluacion']);

    Route::apiResource('evaluaciones', EvaluacionController::class);
    Route::get('evaluaciones/{evaluacion}/alumnos', [EvaluacionController::class, 'alumnos']);

    Route::post('grupos/{grupo}/entregas', [EntregaController::class, 'storeWithoutIdempotency']);
    Route::post('grupos/{grupo}/entregas/idempotent', [EntregaController::class, 'storeWithIdempotency']);
});