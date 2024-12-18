<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class PagamentoVendasOnlineItems extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'pagamentos_vendas_online_itens';
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'produtoId',
        'precoVendaProduto',
        'nomeProduto',
        'quantidade',
        'pagamentoVendasOnlineId',
        'taxaIvaValor',
        'taxaIva',
        'subtotal'
    ];

    public function produto(){
        return $this->belongsTo(Produto::class,'produto_id');
    }



}
