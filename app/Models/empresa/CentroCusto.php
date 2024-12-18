<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class CentroCusto extends Model
{
    protected $connection = 'mysql2';
    protected $table ='centro_custos';

    protected $fillable = [
        'uuid',
        'endereco',
        'empresa_id',
        'status_id',
        'nif',
        'cidade',
        'logotipo',
        'email',
        'website',
        'telefone',
        'pessoa_de_contacto',
        'logotipo',
        'file_alvara',
        'file_nif',
        'nome',
    ];


    public function empresa(){
        return $this->belongsTo(Empresa_Cliente::class, 'empresa_id');
    }

    public function statu()
    {
        return $this->belongsTo(Statu::class, 'status_id');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("nome", "like", $term)
                ->orwhere("nif", "like", $term)
                ->orwhere("telefone", "like", $term);
        });
    }
}
