<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class ExistenciaStock extends Model
{
    protected $table = 'existencias_stocks';
    protected $connection = 'mysql2';

    protected $fillable = [
        'produto_id',
        'armazem_id',
        'tipo_stocagem_id',
        'quantidade',
        'canal_id',
        'user_id',
        'status_id',
        'empresa_id',
        'observacao',
    ];
    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function produtos()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    public function armazens()
    {
        return $this->belongsTo(Armazen::class, 'armazem_id');
    }
    public function armazem()
    {
        return $this->belongsTo(Armazen::class, 'armazem_id');
    }
    public function status()
    {
        return $this->belongsTo(Statu::class, 'status_id');
    }
    public function scopeSearch($query, $term)
    {
        $search = "%$term%";
        if($term == 'null' || !$term){
            $query->whereHas('produto', function ($query) use ($term) {
                $query->where('id', '>=', 1);
            });
        }else{
            $query->whereHas('produto', function ($query) use ($search) {
                $query->where('designacao', 'like', $search);
                $query->orwhere('preco_venda', 'like', $search);
                $query->orwhere('pvp', 'like', $search);
                $query->orwhere('codigo_barra', 'like', $search);
                $query->orwhere('referencia', 'like', $search);
            });
        }
    }
}
