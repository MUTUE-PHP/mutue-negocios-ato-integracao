<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class TipoServico extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'tipos_servicos';

    protected $fillable = [
        'id',
        'designacao',
        'statuId'
    ];

}
