<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;
    public $database = 'alumnos';

    protected $fillable = [
        'legajo',
        'nombres',
        'apellidos',
        'email',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function evaluaciones()
    {
        return $this->belongsToMany(Evaluacion::class, 'evaluaciones_alumnos')
                    ->withPivot('nota', 'fecha_evaluacion')
                    ->withTimestamps();
    }
}
