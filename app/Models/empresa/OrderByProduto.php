<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class OrderByProduto extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'orderbyprodutos';

    protected $fillable = [
        'valor',
        'designacao'
    ];
}
