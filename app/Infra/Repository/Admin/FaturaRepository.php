<?php

namespace App\Infra\Repository\Admin;

use App\Domain\Entity\Admin\FaturaLicenca;
use App\Models\admin\Factura as FaturaDatabase;
use App\Models\admin\FacturaItem as FacturaItemDatabase;
use App\Traits\Admin\TraitSerieDocumentoAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaturaRepository
{
    use TraitSerieDocumentoAdmin;

    public function getFatura($faturaId)
    {
        return FaturaDatabase::where('id', $faturaId)
            ->where('empresa_id', auth()->user()->empresa_id ?? 143)
            ->first();
    }
    public function getUltimaFatura()
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return DB::connection('mysql')->table('facturas')
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('tipo_documento', 'FACTURA')
            ->where('numeracaoFactura', 'like', '%' . $this->mostrarSerieDocumento() . '%')
            ->orderBy('id', 'DESC')->first();
    }
    public function getNumeroSequenciaFatura()
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return DB::connection('mysql')->table('facturas')
                ->where('created_at', 'like', '%' . $yearNow . '%')
                ->where('tipo_documento', 'FACTURA')
                ->where('numeracaoFactura', 'like', '%' . $this->mostrarSerieDocumento() . '%')
                ->orderBy('id', 'DESC')->limit(1)->count() + 1;
    }
    public function emitirFatura(FaturaLicenca $faturaLicenca)
    {
        $fatura = FaturaDatabase::create([
            'total_preco_factura' => $faturaLicenca->getTotalPrecoFatura(),
            'valor_entregue' => $faturaLicenca->getTotalEntregue(),
            'total_sem_imposto' => $faturaLicenca->getTotalSemImposto(),
            'precoLicenca' => $faturaLicenca->getLicenca()->getValor(),
            'valor_a_pagar' => $faturaLicenca->getTotalPagar(),
            'troco' => $faturaLicenca->getTotalTroco(),
            'valor_extenso' => $faturaLicenca->getTotalPorExtenso(),
            'codigo_moeda' => $faturaLicenca->getMoedaId(),
            'desconto' => $faturaLicenca->getTotalDesconto(),
            'total_iva' => $faturaLicenca->getTotalIva(),
            'multa' => $faturaLicenca->getTotalMulta(),
            'nome_do_cliente' => $faturaLicenca->getEmpresa()->getNome(),
            'telefone_cliente' => $faturaLicenca->getEmpresa()->getTelefone(),
            'nif_cliente' => $faturaLicenca->getEmpresa()->getNif(),
            'email_cliente' => $faturaLicenca->getEmpresa()->getEmail(),
            'endereco_cliente' => $faturaLicenca->getEmpresa()->getEndereco(),
            'numeroItems' => $faturaLicenca->getNumeroItems(),
            'licenca_id' => $faturaLicenca->getLicenca()->getId(),
            'observacao' => $faturaLicenca->getObservacao(),
            'retencao' => $faturaLicenca->getTotalRetencao(),
            'retificado' => 'Não',
            'faturaReference' => $faturaLicenca->getFaturaReferencia(),
            'numSequenciaFactura' => $faturaLicenca->getNumeroSequencia(),
            'numeracaoFactura' => $faturaLicenca->getNumeracaoFatura(),
            'tipoFolha' => $faturaLicenca->getTipoFolha(),
            'hashValor' => $faturaLicenca->getValorHash(),
            'formas_pagamento_id' => $faturaLicenca->getFormaPagamento()->getId(),
            'descricao' => 'Licença ' . $faturaLicenca->getLicenca()->getDesignacao(),
            'empresa_id' => $faturaLicenca->getEmpresa()->getId(),
            'canal_id' => $faturaLicenca->getCanalId(),
            'status_id' => $faturaLicenca->getStatusId(),
            'user_id' => auth()->user()->id ?? 1
        ]);
        FacturaItemDatabase::create([
            'descricao_produto' => 'LICENÇA '.Str::upper($faturaLicenca->getLicenca()->getDesignacao()),
            'preco_produto' => $faturaLicenca->getLicenca()->getValor(),
            'quantidade_produto' => $faturaLicenca->getQuantidade(),
            'total_preco_produto' => $faturaLicenca->getLicenca()->getValor(),
            'licenca_id' => $faturaLicenca->getLicenca()->getId(),
            'factura_id' => $fatura->id,
            'desconto_produto' => $faturaLicenca->getTotalDesconto(),
            'retencao_produto' => $faturaLicenca->getTotalRetencao(),
            'incidencia_produto' => $faturaLicenca->getTotalPrecoFatura(),
            'iva_produto' => $faturaLicenca->getTotalIva(),
        ]);
        return $fatura;
    }

}
