<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $table = 'anuncios';
    protected $connection = 'mysql';

    protected  $fillable = [
        'titulo',
        'data_inicio',
        'data_final',
        'descricao',
        'user_id'
    ];

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('empresa', function ($query) use ($term) {
            $query->where('data_final', 'like', $term)
                ->orwhere('data_inicio', 'like', $term);
        });
    }


}
