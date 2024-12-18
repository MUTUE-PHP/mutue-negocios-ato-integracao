<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class ValorCaracteristica extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'valorcaracteristicas';

    public function categoriaCaracteristica()
    {
        return $this->belongsTo(Categoriacaracteristicas::class, 'categoria_caracteristica_id');
    }


}
