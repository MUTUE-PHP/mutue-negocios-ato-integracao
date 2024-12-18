<?php

namespace App\Infra\Repository\Empresa;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\Faturacao\Fatura;
use App\Domain\Entity\Empresa\Faturacao\FaturaEmitida;
use App\Domain\Entity\Empresa\Faturacao\FaturaItemEmitida;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Enums\EnumTipoDocumento;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura as FaturaDatabase;
use App\Models\empresa\FacturaItems;
use App\Models\empresa\FacturaItems as FaturaItemDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaturaRepository
{
    public function existeEstaSequencia(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return FaturaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracaoFactura', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaFactura', $sequenciaDocumento->getSequencia())
            ->where('tipo_documento', EnumTipoDocumento::$FATURA)
            ->first();
    }
    public function getFaturaById($faturaId){
        return FaturaDatabase::with(['facturas_items'])->where('empresa_id', auth()->user()->empresa_id)
            ->where('tipo_documento', 3)
            ->where('id', $faturaId)
            ->first();

    }

    public function getFaturaPelaNumeracao($numeracao)
    {
        return FaturaDatabase::where(function ($query) use ($numeracao) {
            $query->where('numeracaoFactura', 'LIKE', '%' . $numeracao . '%')
                ->orWhere('codigoBarra', 'LIKE', '%' . $numeracao . '%');
        })->where('empresa_id', auth()->user()->empresa_id)
            ->where('tipo_documento', 2)
            ->first();
    }

    public function getFaturaPelaNumeracaoDocumento($numeracaoDocumento)
    {
        return FaturaDatabase::where('numeracaoFactura', $numeracaoDocumento)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();
    }

    public function getHabilitadoCartaGarantia($faturaId)
    {
        return FacturaItems::where('factura_id', $faturaId)
            ->where('produtoCartaGarantia', 'Y')
            ->first();
    }

    public function sequenciaMenorExistentes(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return FaturaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracaoFactura', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaFactura', '>', $sequenciaDocumento->getSequencia())
            ->where('tipo_documento', EnumTipoDocumento::$FATURA)
            ->first();
    }

    public function verificarSeExisteDocumentoSuperiorDataAtual($numeracaoDocumento = "numeracaoFactura")
    {
        $empresaSerie = DB::table('series_documento')->where('empresa_id', auth()->user()->empresa_id)->first();
        $serieDocumento = $empresaSerie ? $empresaSerie->serie : mb_strtoupper(substr(auth()->user()->empresa->nome, 0, 3));
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        $query = DB::table('facturas')->where('empresa_id', auth()->user()->empresa_id)
            ->where($numeracaoDocumento, 'like', '%' . $serieDocumento . '%')
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->orderBy('id', 'DESC')->limit(1)->first();

        if ($query == null) {
            return true;
        }
        if ($query) {
            $dataAnteriorDocumento = Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at);
            $dataAtualDocumento = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            if ($dataAtualDocumento > $dataAnteriorDocumento) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function mostrarSerieDocumento()
    {

        $empresaId = auth()->user() ? auth()->user()->empresa_id : null;
        $documentoSerie = DB::connection('mysql2')->table('series_documento')
            ->where('empresa_id', $empresaId)->first();
        if ($documentoSerie) {
            return mb_strtoupper(substr(Str::slug($documentoSerie->serie), 0, 3));
        }
        return mb_strtoupper(substr(Str::slug(auth()->user()->empresa->nome), 0, 3));
    }

    public function pegarUltimaFactura($tipoDocumento)
    {
        $getAnoFaturacao = new GetAnoDeFaturacao(new DatabaseRepositoryFactory());
        $getYearNow = $getAnoFaturacao->execute();
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        if ($getYearNow) {
            $yearNow = $getYearNow->valor;
        }
        $getNumeroSerieDocumento = new GetNumeroSerieDocumento(new DatabaseRepositoryFactory());
        $numeroSerieDocumento = $getNumeroSerieDocumento->execute();
        if($numeroSerieDocumento){
            $numeroSerieDocumento = $numeroSerieDocumento->valor;
        }else{
            $numeroSerieDocumento = "ATO";
        }

        $resultados = DB::connection('mysql2')->select("SELECT *
          FROM facturas
          WHERE empresa_id = " . auth()->user()->empresa_id . " and  SUBSTRING_INDEX(numeracaoFactura, '/', 1) LIKE '%" . $yearNow . "%' and numeracaoFactura  LIKE '%" . $numeroSerieDocumento . "%'
            AND tipo_documento = $tipoDocumento
          ORDER BY id DESC
          LIMIT 1");

        if (!$resultados) return null;

        return json_decode(json_encode($resultados[0]));
    }

    public function diasVencimentoFacturaProforma()
    {

        //Dias de vencimentos de facturas proforma
        $DiasVencimentoFacturaProforma = DB::connection('mysql2')->table('parametros')->Where('label', 'n_dias_vencimento_factura_proforma')->where("empresa_id", auth()->user()->empresa_id)->first();
        if ($DiasVencimentoFacturaProforma) {
            $vencimentofacturaproforma = $DiasVencimentoFacturaProforma->vida;
        } else {

            $DiasVencimentoFacturaProforma = DB::connection('mysql2')->table('parametros')
                ->Where('label', 'n_dias_vencimento_factura_proforma')
                ->where("empresa_id", NULL)->first();
            $vencimentofacturaproforma = $DiasVencimentoFacturaProforma->vida;
        }

        return $vencimentofacturaproforma;
    }

    public function diasVencimentoFactura()
    {
        //Dias de vencimentos de facturas
        $DiasVencimentoFactura = DB::connection('mysql2')->table('parametros')->Where('label', 'n_dias_vencimento_factura')->where("empresa_id", auth()->user()->empresa_id)->first();
        if ($DiasVencimentoFactura) {
            $vencimentofactura = $DiasVencimentoFactura->vida;
        } else {
            $DiasVencimentoFactura = DB::connection('mysql2')->table('parametros')
                ->Where('label', 'n_dias_vencimento_factura')
                ->where("empresa_id", NULL)->first();
            $vencimentofactura = $DiasVencimentoFactura->vida;
        }
        return $vencimentofactura;
    }

    public function emitirDocumento(FaturaEmitida $fatura)
    {
        return FaturaDatabase::create([
            'total_preco_factura' => $fatura->getTotalPrecoFatura() ?? 0,
            'valor_a_pagar' => $fatura->getTotalPagar() ?? 0,
            'valor_entregue' => $fatura->getTotalEntregue() ?? 0,
            'valor_multicaixa' => $fatura->getTotalMulticaixa() ?? 0,
            'valor_cash' => $fatura->getTotalCash() ?? 0,
            'troco' => $fatura->getTotalTroco() ?? 0,
            'valor_extenso' => $fatura->getTotalPagarExtenso(),
            'texto_hash' => $fatura->getTextoHash(),
            'codigo_moeda' => $fatura->getMoedaId(),
            'desconto' => $fatura->getTotalDesconto() ?? 0,
            'total_iva' => $fatura->getTotalIva() ?? 0,
            'multa' => $fatura->getTotalMulta() ?? 0,
            'nome_do_cliente' => $fatura->getNomeCliente(),
            'numeroCartaoCliente' => $fatura->getNumeroCartaoCliente(),
            'telefone_cliente' => $fatura->getTelefoneCliente(),
            'nif_cliente' => $fatura->getNifCliente(),
            'centroCustoId' => session()->get('centroCustoId'),
            'email_cliente' => $fatura->getEmailCliente(),
            'endereco_cliente' => $fatura->getEnderecoCliente(),
            'conta_corrente_cliente' => $fatura->getContaCorrenteCliente(),
            'numeroItems' => $fatura->getNumeroItems(),
            'tipo_documento' => $fatura->getTipoDocumento(),
            'tipoFolha' => $fatura->getTipoFolha(),
            'retencao' => $fatura->getTotalRetencao() ?? 0,
            'nextFactura' => null,
            'faturaReference' => null,
            'numSequenciaFactura' => $fatura->getNumSequenciaFatura(),
            'numeracaoFactura' => $fatura->getNumeracaoFatura(),
            'hashValor' => $fatura->getHashValor(),
            'retificado' => $fatura->getRetificado(),
            'formas_pagamento_id' => $fatura->getFormaPagamentoId(),
            'descricao' => $fatura->getObservacao(),
            'observacao' => $fatura->getObservacao(),
            'armazen_id' => $fatura->getArmazemId(),
            'cliente_id' => $fatura->getClienteId(),
            'saldoAnteriorCartaoCliente' => $fatura->getSaldoAnterior() ?? 0,
            'saldoAtualCartaoCliente' => $fatura->getSaldoCliente() ?? 0,
            'bonusDescontoCartaoCliente' => $fatura->getBonus() ?? 0,
            'valorBonusCartaoCliente' => $fatura->getValorBonus() ?? 0,
            'totalDescontarCartao' => $fatura->getValorDescontarSaldo() ?? 0,
            'canal_id' => $fatura->getCanalId(),
            'status_id' => $fatura->getStatusId(),
            'empresa_id' => auth()->user()->empresa_id,
            'user_id' => auth()->user()->id,
            'operador' => auth()->user()->name,
            'data_vencimento' => $fatura->getDataVencimento(),
            'total_incidencia' => $fatura->getTotalIncidencia() ?? 0,
            'statusFactura' => $fatura->getStatusFactura(),
            'tipo_user_id' => 2,
            'notaEntrega' => $fatura->getNotaEntrega()
        ]);
    }

    public function salvarItemDocumento(FaturaItemEmitida $faturaItemEmitida)
    {

        return FaturaItemDatabase::create([
            'descricao_produto' => Str::title($faturaItemEmitida->getNomeProduto()),
            'preco_compra_produto' => $faturaItemEmitida->getPrecoCompraProduto() ?? 0,
            'produtoCartaGarantia' => $faturaItemEmitida->getProdutoCartaGarantia(),
            'tempoGarantiaProduto' => $faturaItemEmitida->getTempoGarantiaProduto(),
            'preco_venda_produto' => $faturaItemEmitida->getPrecoVendaProduto() ?? 0,
            'quantidade_produto' => $faturaItemEmitida->getQuantidadeProduto(),
            'desconto_taxa' => $faturaItemEmitida->getDescontoTaxa() ?? 0,
            'desconto_produto' => $faturaItemEmitida->getDescontoProduto() ?? 0,
            'quantidade_anterior' => $faturaItemEmitida->getQuantidadeAnteriorProduto(),
            'incidencia_produto' => $faturaItemEmitida->getIncidenciaProduto() ?? 0,
            'retencao_produto' => $faturaItemEmitida->getRetencaoProduto() ?? 0,
            'taxa' => $faturaItemEmitida->getTaxaProduto(),
            'iva_produto' => $faturaItemEmitida->getIvaProduto() ?? 0,
            'total_preco_produto' => $faturaItemEmitida->getTotalPrecoProduto() ?? 0,
            'produto_id' => $faturaItemEmitida->getProdutoId(),
            'factura_id' => $faturaItemEmitida->getFaturaId(),
            'armazem_id' => $faturaItemEmitida->getArmazemId(),
        ]);
    }

}
