<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'imagen',
        'estado',
        'usuario_id',
    ];

    // OBTENER LA IMAGE
    public function getImagenUrl(){
        if($this->imagen && $this->imagen != 'default.png' && $this->imagen != null){
            return asset('imagenes/categorias/' . $this->imagen);
        } else {
            return asset('default.png');
        }
    }


    // RELACION CON USUARIOS
    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // RELACION CON POSTS
    public function posts(){
        return $this->hasMany(Posts::class, 'categoria_id');
    }

}
