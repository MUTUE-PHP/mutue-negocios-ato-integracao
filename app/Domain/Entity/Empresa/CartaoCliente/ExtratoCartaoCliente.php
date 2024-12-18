<?php

namespace App\Domain\Entity\Empresa\CartaoCliente;

class ExtratoCartaoCliente
{

    private $clienteId;
    private $bonus;
    private $operacao;
    private $saldo_anterior;
    private $saldo_atual;
    private $valorBonus;
    private $valorDescontarCartao;
    private $documetoReferente;
    private $updateBonus;

    /**
     * @param $clienteId
     * @param $bonus
     * @param $operacao
     * @param $saldo_anterior
     * @param $saldo_atual
     * @param $valor
     * @param $documetoReferente
     */
    public function __construct($clienteId, $bonus, $operacao, $saldo_anterior, $saldo_atual, $valorBonus,$valorDescontarCartao, $documetoReferente, $updateBonus = false)
    {
        $this->clienteId = $clienteId;
        $this->bonus = $bonus;
        $this->operacao = $operacao;
        $this->saldo_anterior = $saldo_anterior;
        $this->saldo_atual = $saldo_atual;
        $this->valorBonus = $valorBonus;
        $this->valorDescontarCartao = $valorDescontarCartao;
        $this->documetoReferente = $documetoReferente;
        $this->updateBonus = $updateBonus;
    }

    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * @return mixed
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * @return mixed
     */
    public function getOperacao()
    {
        return $this->operacao;
    }

    /**
     * @return mixed
     */
    public function getSaldoAnterior()
    {
        return $this->saldo_anterior;
    }

    /**
     * @return mixed
     */
    public function getSaldoAtual()
    {
        return $this->saldo_atual;
    }

    /**
     * @return mixed
     */
    public function getValorBonus()
    {
        return $this->valorBonus;
    }
    public function getValorDescontarCartao(){
        return $this->valorDescontarCartao;
    }

    /**
     * @return mixed
     */
    public function getDocumetoReferente()
    {
        return $this->documetoReferente;
    }
    public function getUpdateBonus()
    {
        return $this->updateBonus;
    }
}
