<?php

namespace App\Infra\Repository\Admin;

use App\Domain\Entity\Admin\PagamentoLicenca;
use App\Models\admin\Pagamento as PagamentoDatabase;
use App\Traits\Admin\TraitSerieDocumentoAdmin;
use Carbon\Carbon;

class PagamentoLicencaRepository
{
    use TraitSerieDocumentoAdmin;

    public function fazerPagamentoFatura(PagamentoLicenca $pagamentoLicenca){

        return PagamentoDatabase::create([
            'valor_depositado' => $pagamentoLicenca->getValorDepositar(),
            'quantidade' => $pagamentoLicenca->getQuantidade(),
            'totalPago' => $pagamentoLicenca->getTotalPago(),
            'data_pago_banco' => $pagamentoLicenca->getDataPagamentoBanco(),
            'numero_operacao_bancaria' => $pagamentoLicenca->getNumeroOperacaoBancaria(),
            'numeracao_recibo' => $pagamentoLicenca->getNumeracaoRecibo(),
            'hash' => $pagamentoLicenca->getHashValor(),
            'texto_hash' => $pagamentoLicenca->getTextoHash(),
            'valor_extenso' => $pagamentoLicenca->getValorExtenso(),
            'numSequenciaRecibo' => $pagamentoLicenca->getNumSequencia(),
            'forma_pagamento_id' => $pagamentoLicenca->getFormaPagamentoId(),
            'conta_movimentada_id' => $pagamentoLicenca->getContaMovimentadaId(),
            'comprovativo_bancario' => $pagamentoLicenca->getComprovativoBancario(),
            'observacao' => $pagamentoLicenca->getObservacao(),
            'factura_id' => $pagamentoLicenca->getFacturaId(),
            'empresa_id' => $pagamentoLicenca->getEmpresaId(),
            'canal_id' => 2,
            'user_id' => auth()->user()->id??1,
            'status_id' => $pagamentoLicenca->getStatusId(),
            'data_validacao' => $pagamentoLicenca->getDataValidacao(),
            'descricao' => $pagamentoLicenca->getObservacao(),
            'nFactura' => $pagamentoLicenca->getNFactura(),
            'status' => "VÁLIDO",

        ]);
    }
    public function getPagamentos($filtro){
        return PagamentoDatabase::with(['empresa','fatura', 'ativacaoLicenca'])->latest()
            ->filter($filtro)
            ->search(trim($filtro['search']))
        ->paginate();
    }
    public function getPagamento($pagamentoId){
        return PagamentoDatabase::with(['empresa','fatura', 'ativacaoLicenca'])
            ->where('id', $pagamentoId)
            ->first();
    }
    public function getUltimoRecibo(){
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return  PagamentoDatabase::where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracao_recibo', 'like', '%' . $this->mostrarSerieDocumento() . '%')
            ->orderBy('id', 'DESC')->first();
    }
    public function getNumeroSequenciaRecibo(){
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return  PagamentoDatabase::where('created_at', 'like', '%' . $yearNow . '%')
                ->where('numeracao_recibo', 'like', '%' . $this->mostrarSerieDocumento() . '%')
                ->orderBy('id', 'DESC')->limit(1)->count() + 1;
    }
    public function verificarExistenciaNumeroOperacaoBancaria($operacaoBancaria){
        return PagamentoDatabase::where('numero_operacao_bancaria', $operacaoBancaria)->first();
    }

}
