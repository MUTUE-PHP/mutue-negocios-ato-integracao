<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class EspecificacaoMercadoria extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'especificacao_mercadorias';

    protected $fillable = [
        "designacao",
        "desconto",
        "status" 
    ];

}