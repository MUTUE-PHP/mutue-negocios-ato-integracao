<?php

namespace App\empresa;

use Illuminate\Database\Eloquent\Model;

class EspecificacaoMercadoria extends Model
{
    //
    protected $fillable = [
        "designacao",
        "desconto",
        "status" 
    ];
}