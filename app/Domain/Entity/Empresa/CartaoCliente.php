<?php

namespace App\Domain\Entity\Empresa;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class CartaoCliente
{
    private $clienteId;
    private $saldo;
    private $numeroCartao;
    private $dataEmissao;
    private $dataValidade;
    private $numeracaoSequencia;

    /**
     * @param $clienteId
     * @param $numeroCartao
     * @param $dataEmissao
     * @param $dataValidade
     */
    public function __construct($clienteId, $saldo, $numeroCartao, $dataEmissao, $dataValidade, $numeracaoSequencia)
    {
        $this->clienteId = $clienteId;
        $this->saldo = $saldo;
        $this->numeroCartao = $numeroCartao;
        $this->dataEmissao = $dataEmissao;
        $this->dataValidade = $dataValidade;
        $this->numeracaoSequencia = $numeracaoSequencia;
    }

    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }


    /**
     * @return mixed
     */
    public function getNumeroCartao()
    {
        return $this->numeroCartao;
    }

    /**
     * @return mixed
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    public function getDataValidade()
    {
        return $this->dataValidade;
    }

    public function getNumeracaoSequencia()
    {
        return $this->numeracaoSequencia;
    }
    public function isValid($dataHoje = null)
    {
        $dataHoje = $dataHoje ?? Carbon::now();
        return $this->dataValidade > $dataHoje;
    }
    public function bonusCompra($totalPagar, $bonusPercentagem){
        if(!$this->isValid()) return 0;
        return ($totalPagar * $bonusPercentagem) / 100;
    }
}
