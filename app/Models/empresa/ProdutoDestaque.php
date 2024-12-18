<?php

namespace App\Models\empresa;
use Illuminate\Database\Eloquent\Model;
class ProdutoDestaque extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'produtos_destaque';

    protected $fillable = [
        'id',
        'uuid',
        'produto_id',
        'designacao',
        'descricao',
        'empresa_id',
        'created_at',
        'updated_at',
    ];

    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function scopeOrderByFilter($query, $term){

        if(!$term) return $query->where('id','>', 0);
        if($term == 'min'){
            return $query->whereHas('produto', function ($query) use ($term) {
                $query->orderBy('preco_venda', 'asc');
            });

        }else if($term == 'max'){
            return $query->whereHas('produto', function ($query) use ($term) {
                return $query->orderBy('preco_venda', 'desc');
            });
        }else if($term == 'desc'){
            return $query->whereHas("produto", function($query){
                $query->orderBy('designacao', 'desc');
            });

        }else if($term == 'asc'){
            return $query->whereHas("produto", function($query){
                $query->orderBy('designacao', 'asc');
            });

        }
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("designacao", "like", $term)
                ->orwhere("descricao", "like", $term);
        });
    }
}
