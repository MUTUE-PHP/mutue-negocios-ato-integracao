<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class TipoMercadoria extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'tipos_mercadoria';

    protected $fillable = [
        'id',
        'designacao',
        'taxa',
        'statuId'
    ];

}
