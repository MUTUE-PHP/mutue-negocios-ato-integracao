<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $connection = 'mysql2';
    protected $table ='banner';

    protected $fillable = [
        'nome',
        'descricao',
        'imagens',
        'status_id',
        
    ];


    public function statuGeral()
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
