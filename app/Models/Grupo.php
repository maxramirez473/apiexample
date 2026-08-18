<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    public $database = 'grupos';

    protected $fillable = [
        'nombre',
        'tp_integrador'
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function entregas()
    {
        return $this->belongsToMany(Entrega::class, 'entregas_grupos')
                    ->withPivot('fecha_entrega', 'archivo', 'comentario')
                    ->withTimestamps();
    }
}
