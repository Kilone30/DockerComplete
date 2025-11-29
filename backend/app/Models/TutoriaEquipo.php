<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoriaEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'colaborativo',
        'licenciatura_id'
    ];

    public function licenciaturas()
    {
        return $this->belongsToMany(Licenciatura::class);
    }

    public function docentes()
    {
        return $this->hasMany(Docente::class, 'docentes_tutoria_equipos')->using(TutorEquipo::class);
    }
}
