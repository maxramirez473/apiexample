<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    public $database = 'evaluaciones';

    protected $fillable = [
        'nombre',
        'nota_minima_aprobacion',
        'nota_minima_promocion',
    ];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'evaluaciones_alumnos')
                    ->withPivot('nota', 'fecha_evaluacion')
                    ->withTimestamps();
    }
}
