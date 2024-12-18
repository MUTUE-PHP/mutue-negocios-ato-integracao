<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class CartaoCliente extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'cartao_clientes';

    protected $fillable = [
        'id',
        'clienteId',
        'saldo',
        'numeroCartao',
        'dataEmissao',
        'dataValidade',
        'codigoBarra',
        'numeracaoSequencia',
        'empresaId',
        'centroCustoId',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clienteId');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa_Cliente::class, 'empresaId');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('cliente', function($query) use($term){
            $query->where("nome", "like", $term);

        })->orwhere(function ($query) use ($term) {
            $query->where("numeroCartao", "like", $term);
        });
    }
}
