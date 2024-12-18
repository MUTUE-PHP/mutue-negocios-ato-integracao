<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class AtualizacaoStocks extends Model
{
    protected $connection = 'mysql2';
    protected $table ='actualizacao_stocks';

    protected $fillable = [
        'empresa_id',
        'produto_id',
        'quantidade_antes',
        'quantidade_nova',
        'user_id',
        'tipo_user_id',
        'canal_id',
        'status_id',
        'armazem_id',
        'descricao',
        'centroCustoId'
    ];


    public function produtos(){
        return $this->belongsTo(Produto::class,'produto_id');
    }
    public function armazens(){
        return $this->belongsTo(Armazen::class,'armazem_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function status(){
        return $this->belongsTo(Statu::class,'status_id');
    }
    public function scopeFilter($query, $term)
    {
        $search = trim($term['search']) !== "" ? trim($term['search']) : null;
        $armazemId = $term['armazemId'] !== "" ? $term['armazemId'] : null;
        $centroCustoId = $term['centroCustoId'] !== "" ? $term['centroCustoId'] : null;

        return $query->where(function ($query) use ($search, $armazemId, $centroCustoId) {
            if($armazemId){
                $query->where('armazem_id', $armazemId);
            }
            if($centroCustoId){
                $query->where('centroCustoId', $centroCustoId);
            }
            if($search){
                $query->whereHas('produtos', function($query) use ($search){
                    $query->where('designacao','like', '%' . $search . '%');

                });
            }
        });
    }
}
