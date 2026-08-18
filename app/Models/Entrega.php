<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    public $database = 'entregas';

    protected $fillable = [
        'nombre',
        'fecha_entrega',
    ];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'entregas_grupos')
                    ->withPivot('fecha_entrega', 'archivo', 'comentario')
                    ->withTimestamps();
    }
}
