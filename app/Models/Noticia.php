<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    public $timestamps = false;

    protected $fillable = [
        'id_jornalista',
        'id_tipo',
        'titulo',
        'descricao',
        'corpo_da_noticia'
    ];
}
