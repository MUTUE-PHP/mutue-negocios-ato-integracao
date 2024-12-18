<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class Factura extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'facturas';
    protected $primarykey = 'id';
    protected $guard = 'id';

    protected $fillable = [
        'id',
        'total_preco_factura',
        'valor_a_pagar',
        'valor_entregue',
        'valor_multicaixa',
        'valor_cash',
        'troco',
        'valor_extenso',
        'texto_hash',
        'codigo_moeda',
        'desconto',
        'total_iva',
        'multa',
        'nome_do_cliente',
        'numeroCartaoCliente',
        'telefone_cliente',
        'nif_cliente',
        'centroCustoId',
        'email_cliente',
        'endereco_cliente',
        'conta_corrente_cliente',
        'numeroItems',
        'tipo_documento',
        'tipoFolha',
        'retencao',
        'nextFactura',
        'faturaReference',
        'numSequenciaFactura',
        'numeracaoFactura',
        'numeracaoProforma',
        'hashValor',
        'retificado',
        'formas_pagamento_id',
        'descricao',
        'observacao',
        'observacaoFacturaAluno',
        'pagamento_venda_online_id',
        'armazen_id',
        'cliente_id',
        'empresa_id',
        'canal_id',
        'status_id',
        'user_id',
        'operador',
        'convertidoFactura',
        'data_vencimento',
        'total_incidencia',
        'tipo_user_id',
        'statusFactura',
        'anulado',
        'saldoAnteriorCartaoCliente',
        'saldoAtualCartaoCliente',
        'bonusDescontoCartaoCliente',
        'valorBonusCartaoCliente',
        'totalDescontarCartao',
        'centroCustoId',
        'notaEntrega',
        'nifEmpresa'
    ];

    const FACTURARECIBO = 1;
    const FACTURA = 2;
    const FACTURAPROFORMA = 3;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->faturaReference = mb_strtoupper(Keygen::numeric(9)->generate());
            $model->nextFactura = $model->faturaReference;
        });
    }

    public function status()
    {
        return $this->belongsTo(Statu::class, 'status_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function facturas_items()
    {
        return $this->hasMany(FacturaItems::class, 'factura_id', 'id');
    }
    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamentoGeral::class, 'formaPagamentoId');
    }
    public function notaCredito()
    {
        return $this->hasOne(NotaCredito::class, 'facturaId');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento');
    }
    public function tipoDocumentoSigla()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento');
    }
    public function tipoDocumentoFatura()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipoDocumento');
    }
    public function empresa()
    {
        return $this->hasOne(Empresa_Cliente::class, 'id', 'empresa_id');
    }
    public function tipoUser()
    {
        return $this->belongsTo(TipoUser::class, 'tipo_user_id');
    }
    public function armazem()
    {
        return $this->belongsTo(Armazen::class, 'armazen_id');
    }

    public function canal()
    {
        return $this->belongsTo(CanalComunicacao::class, 'canal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query, $term)
    {
        $search = trim($term['search']) !== "" ? trim($term['search']) : null;
        $centroCustoId = $term['centroCustoId'] !== "" ? $term['centroCustoId'] : null;
        $tipoDocumentoId = $term['tipoDocumentoId'] !== "" ? $term['tipoDocumentoId'] : null;
        $dataInicial = $term['dataInicial'] !== "" ? $term['dataInicial'] : null;
        $dataFinal = $term['dataFinal'] !== "" ? $term['dataFinal'] : null;

        return $query->where(function ($query) use ($search, $tipoDocumentoId, $centroCustoId, $dataInicial, $dataFinal) {

            if($dataInicial && !$dataFinal){
                $query->whereDate('created_at', $dataInicial);
            }
            if($dataInicial && $dataFinal){
                $query->whereDate('created_at', '>=', $dataInicial)
                    ->whereDate('created_at', '<=', $dataFinal);
            }
            if ($tipoDocumentoId) {
                $query->where('tipo_documento', $tipoDocumentoId);
            }
            if ($centroCustoId) {
                $query->where('centroCustoId', $centroCustoId);
            }
            if ($centroCustoId) {
                $query->where('centroCustoId', $centroCustoId);
            }
            if ($search) {
                $query->where('numeracaoFactura', 'like', '%' . $search . '%')
                    ->orwhere("telefone_cliente", "like", $search)
                    ->orwhere("nome_do_cliente", "like", $search)
                    ->orwhere("nif_cliente", "like", $search)
                    ->orwhere("email_cliente", "like", $search);
            }
        });
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("nome_do_cliente", "like", $term)
                ->orwhere("telefone_cliente", "like", $term)
                ->orwhere("nif_cliente", "like", $term)
                ->orwhere("email_cliente", "like", $term)
            ->orwhere("numeracaoFactura", "like", $term);
        });
    }

}
