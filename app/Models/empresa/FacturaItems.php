<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class FacturaItems extends Model
{
    protected $connection = 'mysql2';
    protected $table ='factura_items';
    const CREATED_AT = NULL;
    protected $fillable = [
        'descricao_produto',
        'preco_compra_produto',
        'preco_venda_produto',
        'produtoCartaGarantia',
        'tempoGarantiaProduto',
        'quantidade_produto',
        'desconto_taxa',
        'desconto_produto',
        'quantidade_anterior',
        'incidencia_produto',
        'retencao_produto',
        'taxa',
        'iva_produto',
        'total_preco_produto',
        'produto_id',
        'factura_id',
        'armazem_id'
    ];
    const UPDATED_AT = NULL;
    public function produto(){
        return $this->belongsTo(Produto::class,'produtoId');
    }
    public function factura(){
        return $this->belongsTo(Factura::class,'factura_id');
    }
}
