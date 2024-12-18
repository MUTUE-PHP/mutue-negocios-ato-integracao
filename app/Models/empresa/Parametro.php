<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'parametros';
    public $timestamps = false;

    protected $fillable = [
        'designacao',
        'valor',
        'vida',
        'valorSelects',
        'empresa_id',
        'label',
        'type'
    ];
}
