<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'nombre',
        'estado',
        'usuario_id',
    ];

    // RELACION CON USUARIOS
    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
