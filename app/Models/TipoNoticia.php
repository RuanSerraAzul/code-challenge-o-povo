<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoNoticia extends Model
{
    use HasFactory;

    protected $table = 'tipo_noticias';

    protected $fillable = [
        'id_jornalista',
        'nome'
    ];

    public $timestamps = false;
}
