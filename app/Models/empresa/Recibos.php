<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class Recibos extends Model
{
    protected $table = 'recibos';
    protected $connection = 'mysql2';

    protected  $fillable = [
        'numeracaoRecibo',
        'clienteId',
        'anulado',
        'totalEntregue',
        'userId',
        'empresaId',
        'nifEmpresa',
        'nomeEmpresa',
        'facturaId',
        'totalFatura',
        'totalDebitado',
        'totalImposto',
        'totalDebitar',
        'formaPagamentoId',
        'observacao',
        'numSequenciaRecibo',
        'nomeCliente',
        'telefoneCliente',
        'nifCliente',
        'emailCliente',
        'enderecoCliente',
        'dataOperacao',
        'numeroOperacaoBancaria',
        'comprovativoBancario',
        'hash'
    ];


    // public function recibos_items()
    // {
    //     return $this->hasMany(RecibosItem::class, 'recibo_id', 'id');
    // }
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'facturaId');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa_cliente::class, 'empresa_id');
    }
    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamentoGeral::class, 'formaPagamentoId');
    }
    public function tipoUser()
    {
        return $this->belongsTo(TipoUser::class, 'tipo_user_id');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("nomeCliente", "like", $term)
            ->orwhere("numeracaoRecibo", "like", $term);
        });
    }
}
