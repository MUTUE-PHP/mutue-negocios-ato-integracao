<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class ValorCaracteristicasProdutos extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'valorCaracteristicas_produtos';

    public function valorCarateristica(){
        return $this->belongsTo(ValorCaracteristica::class, 'valor_caracteristica_id');
    }
}
