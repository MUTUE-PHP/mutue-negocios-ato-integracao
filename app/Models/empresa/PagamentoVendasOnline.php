<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class PagamentoVendasOnline extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'pagamentos_vendas_online';

    protected $fillable = [
        'id',
        'uuid',
        'codigo',
        'bancoId',
        'dataPagamentoBanco',
        'totalPagamento',
        'totalDesconto',
        'totalIva',
        'tipoEntregaId',
        'taxaEntrega',
        'estimativaEntrega',
        'comprovativoBancario',
        'formaPagamentoId',
        'userId',
        'nomeUser',
        'statusPagamentoId',
        'nomeUserEntrega',
        'apelidoUserEntrega',
        'enderecoEntrega',
        'pontoReferenciaEntrega',
        'telefoneUserEntrega',
        'provinciaIdEntrega',
        'municipioIdEntrega',
        'emailEntrega',
        'observacaoEntrega',
        'numeroCartaoCliente',
        'created_at',
        'updated_at',
        'deleted_at',
        'empresaId',
    ];
    public function banco(){
        return $this->belongsTo(Banco::class, 'bancoId');
    }
    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
    public function statu()
    {
        return $this->belongsTo(StatusPagamentoVendaOnline::class, 'statusPagamentoId');
    }
    public function comuna()
    {
        return $this->belongsTo(ComunasFrete::class, 'comunaId');
    }
    public function tipoEntrega()
    {
        return $this->belongsTo(TiposEntrega::class, 'tipoEntregaId');
    }
    public function pagamentoVendasOnlineItems()
    {
        return $this->hasMany(PagamentoVendasOnlineItems::class, 'pagamentoVendasOnlineId', 'id');
    }
    public function scopeSearch($query, $term)
    {
        $search = "%$term%";

        $query->where(function ($query) use ($search) {
            $query->where('codigo', 'like', '%' . $search . '%')
                ->orwhere("enderecoEntrega", "like", $search)
                ->orwhere("nomeUserEntrega", "like", $search)
                ->orwhere("telefoneUserEntrega", "like", $search)
                ->orwhere("emailEntrega", "like", $search)
                ->orwhere("numeroCartaoCliente", "like", $search);
        });
    }
    public function scopeFilter($query, $term)
    {
        $search = trim($term['search']) !== "" ? trim($term['search']) : null;
        $status = $term['status'] !== "" ? $term['status'] : null;
        $dataInicial = $term['dataInicial'] !== "" ? $term['dataInicial'] : null;
        $dataFinal = $term['dataFinal'] !== "" ? $term['dataFinal'] : null;
        $tipoEntregaId = $term['tipoEntregaId'] !== "" ? $term['tipoEntregaId'] : null;
        $orderBy = $term['orderBy'] !== "" ? $term['orderBy'] : 'desc';

        return $query->where(function ($query) use ($search, $status, $dataInicial, $dataFinal, $tipoEntregaId) {

            if($dataInicial && !$dataFinal){
                $query->whereDate('created_at', $dataInicial);
            }
            if($dataInicial && $dataFinal){
                $query->whereDate('created_at', '>=', $dataInicial)
                    ->whereDate('created_at', '<=', $dataFinal);
            }
            if ($status) {
                $query->where('statusPagamentoId', $status);
            }
            if ($tipoEntregaId) {
                $query->where('tipoEntregaId', $tipoEntregaId);
            }
            if ($search) {
                $query->where('codigo', 'like', '%' . $search . '%')
                    ->orwhere("enderecoEntrega", "like", $search)
                    ->orwhere("nomeUserEntrega", "like", $search)
                    ->orwhere("telefoneUserEntrega", "like", $search)
                    ->orwhere("emailEntrega", "like", $search)
                    ->orwhere("numeroCartaoCliente", "like", $search);
            }
        });
    }
}
