<?php

namespace App\Domain\Entity\Admin;

use Carbon\Carbon;

class PagamentoLicenca
{
    private $empresaId;
    private $facturaId;
    private $valorDepositar;
    private $totalPago;
    private $quantidade;
    private $dataPagamentoBanco;
    private $numeroOperacaoBancaria;
    private $numeracaoRecibo;
    private $hashValor;
    private $textoHash;
    private $valorExtenso;
    private $numSequencia;
    private $formaPagamentoId;
    private $contaMovimentadaId;
    private $comprovativoBancario;
    private $observacao;
    private $statusId;
    private $dataValidacao;
    private $nFactura;

    /**
     * @param $empresaId
     * @param $facturaId
     * @param $valorDepositar
     * @param $totalPago
     * @param $quantidade
     * @param $dataPagamentoBanco
     * @param $numeroOperacaoBancaria
     * @param $numeracaoRecibo
     * @param $hashValor
     * @param $textoHash
     * @param $valorExtenso
     * @param $numSequencia
     * @param $formaPagamentoId
     * @param $contaMovimentadaId
     * @param $comprovativoBancario
     * @param $observacao
     * @param $statusId
     * @param $dataValidacao
     * @param $nFactura
     */
    public function __construct($empresaId, $facturaId, $valorDepositar, $totalPago, $quantidade, $dataPagamentoBanco, $numeroOperacaoBancaria, $numeracaoRecibo, $hashValor, $textoHash, $valorExtenso, $numSequencia, $formaPagamentoId, $contaMovimentadaId, $comprovativoBancario, $observacao, $statusId, $dataValidacao, $nFactura)
    {
        $this->empresaId = $empresaId;
        $this->facturaId = $facturaId;
        $this->valorDepositar = $valorDepositar;
        $this->totalPago = $totalPago;
        $this->quantidade = $quantidade;
        $this->dataPagamentoBanco = $dataPagamentoBanco;
        $this->numeroOperacaoBancaria = $numeroOperacaoBancaria;
        $this->numeracaoRecibo = $numeracaoRecibo;
        $this->hashValor = $hashValor;
        $this->textoHash = $textoHash;
        $this->valorExtenso = $valorExtenso;
        $this->numSequencia = $numSequencia;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->contaMovimentadaId = $contaMovimentadaId;
        $this->comprovativoBancario = $comprovativoBancario;
        $this->observacao = $observacao;
        $this->statusId = $statusId;
        $this->dataValidacao = $dataValidacao;
        $this->nFactura = $nFactura;
    }

    /**
     * @return mixed
     */
    public function getEmpresaId()
    {
        return $this->empresaId;
    }

    /**
     * @return mixed
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * @return mixed
     */
    public function getValorDepositar()
    {
        return $this->valorDepositar;
    }

    /**
     * @return mixed
     */
    public function getTotalPago()
    {
        return $this->totalPago;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade?? 1;
    }

    /**
     * @return mixed
     */
    public function getDataPagamentoBanco()
    {
        $dataBanco = date('Y/m/d H:i:s',strtotime($this->dataPagamentoBanco));
        $dataHoje = date('Y/m/d H:i:s',strtotime(Carbon::now()));
        if($dataBanco > $dataHoje) throw new \Exception("Data pagamento no banco não deve superior a data de hoje");
        return $this->dataPagamentoBanco;
    }
    public function getNumeroOperacaoBancaria()
    {
        return $this->numeroOperacaoBancaria;
    }
    public function getNumeracaoRecibo()
    {
        return $this->numeracaoRecibo;
    }
    public function getHashValor()
    {
        return $this->hashValor;
    }
    public function getTextoHash()
    {
        return $this->textoHash;
    }
    public function getValorExtenso()
    {
        return $this->valorExtenso;
    }

    public function getNumSequencia()
    {
        return $this->numSequencia;
    }

    public function getFormaPagamentoId()
    {
        return $this->formaPagamentoId;
    }
    public function getContaMovimentadaId()
    {
        return $this->contaMovimentadaId;
    }
    public function getComprovativoBancario()
    {
        return $this->comprovativoBancario;
    }
    public function getObservacao()
    {
        return $this->observacao;
    }
    public function getStatusId()
    {
        return $this->statusId;
    }

    public function getDataValidacao()
    {
        return $this->dataValidacao;
    }
    public function getNFactura()
    {
        return $this->nFactura;
    }
}
