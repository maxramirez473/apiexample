<?php

use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\EvaluacionController;
use App\Http\Controllers\Api\GrupoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('grupos', GrupoController::class);
Route::get('grupos/{grupo}/alumnos', [GrupoController::class, 'alumnos']);

Route::apiResource('alumnos', AlumnoController::class);
Route::get('alumnos/{alumno}/evaluaciones', [AlumnoController::class, 'evaluaciones']);
Route::post('alumnos/{alumno}/evaluaciones', [AlumnoController::class, 'addEvaluacion']);

Route::apiResource('evaluaciones', EvaluacionController::class);
Route::get('evaluaciones/{evaluacion}/alumnos', [EvaluacionController::class, 'alumnos']);
