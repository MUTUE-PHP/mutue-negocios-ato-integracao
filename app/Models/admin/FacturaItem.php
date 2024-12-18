<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class FacturaItem extends Model
{
    protected $connection = 'mysql';
    protected $table ='factura_items';
    public $timestamps = false;


    const STATUDIVIDA = 1;
    const STATUPAGO = 2;


    protected $fillable = [
        'descricao_produto',
        'preco_produto',
        'quantidade_produto',
        'total_preco_produto',
        'licenca_id',
        'factura_id',
        'desconto_produto',
        'retencao_produto',
        'incidencia_produto',
        'iva_produto'
    ];
}
