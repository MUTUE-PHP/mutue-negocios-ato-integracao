<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Factura;
use App\Models\admin\Empresa;

class Pagamento extends Model
{
    protected $connection = 'mysql';
    protected $table ='pagamentos';
    const ATIVO = 1;


    protected $fillable = [
        'valor_depositado',
        'quantidade',
        'totalPago',
        'data_pago_banco',
        'numero_operacao_bancaria',
        'numeracao_recibo',
        'hash',
        'texto_hash',
        'valor_extenso',
        'numSequenciaRecibo',
        'forma_pagamento_id',
        'conta_movimentada_id',
        'referenciaFactura',
        'comprovativo_bancario',
        'observacao',
        'factura_id',
        'empresa_id',
        'canal_id',
        'user_id',
        'status_id',
        'created_at',
        'data_validacao',
        'data_rejeitacao',
        'updated_at',
        'descricao',
        'nFactura',
        'status'
    ];
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }
    public function contaMovimento()
    {
        return $this->belongsTo(Bancos::class, 'conta_movimentada_id');
    }
    public function fatura(){
        return $this->belongsTo(Factura::class, 'factura_id');
    }
    public function ativacaoLicenca(){
        return $this->belongsTo(AtivacaoLicenca::class, 'id', 'pagamento_id');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('empresa', function ($query) use ($term) {
            $query->where('nome', 'like', $term)
                ->orwhere('nif', 'like', $term);
        });
    }
    public function scopeFilter($query, $term)
    {
//        dd($term);
        $dataInicial = $term['dataInicial'] !== "" ? $term['dataInicial'] : null;
        $dataFinal = $term['dataFinal'] !== "" ? $term['dataFinal'] : null;

        return $query->where(function ($query) use ($dataInicial, $dataFinal) {

//            if($dataInicial && !$dataFinal){
//                $query->whereDate('created_at', $dataInicial);
//            }
            if($dataInicial && $dataFinal){
                $query->whereDate('created_at', '>=', $dataInicial)
                    ->whereDate('created_at', '<=', $dataFinal);
            }
        });
    }

}
