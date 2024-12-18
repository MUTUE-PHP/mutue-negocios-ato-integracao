<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class NotaEntrega extends Model
{
    protected $connection = 'mysql2';
    protected $table = "notas_entregas";

    protected $fillable = [
        'numeracao_documento',
        'operador_nome',
        'operador_id',
        'factura_id',
        'empresa_id'
    ];

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where("numeracao_documento", "like", $term);
            $query->orwhere("operador_nome", "like", $term);
        });
    }

}
