<?php

namespace App\Domain\Entity\Empresa\Faturacao;

class FaturaEmitida
{
    public $totalPrecoFatura;
    public $totalPagar;
    public $totalEntregue;
    public $totalMulticaixa;
    public $totalCash;
    public $totalTroco;
    public $totalIncidencia;
    public $totalPagarExtenso;
    public $textoHash;
    public $moedaId = 1;
    public $totalDesconto;
    public $totalIva;
    public $totalMulta = 0;
    public $nomeCliente;
    public $bonus;
    public $valorBonus;
    public $saldoAnterior;
    public $aplicadoCartaoCliente;
    public $numeroCartaoCliente;
    public $saldoCliente;
    public $telefoneCliente;
    public $nifCliente;
    public $emailCliente;
    public $enderecoCliente;
    public $contaCorrenteCliente;
    public $numeroItems;
    public $tipoDocumento;
    public $tipoFolha;
    public $totalRetencao;
    public $numSequenciaFatura;
    public $numeracaoFatura;
    public $hashValor;
    public $retificado = "Não";
    public $formaPagamentoId;
    public $armazemId;
    public $clienteId;
    public $canalId = 2;
    public $statusId = 1;
    public $statusFactura = null;
    public $dataVencimento = null;
    public $observacao;
    public $isNovaEntrega;

    /**
     * @param $totalPrecoFatura
     * @param $totalPagar
     * @param $totalEntregue
     * @param $totalMulticaixa
     * @param $totalCash
     * @param $totalTroco
     * @param $totalIncidencia
     * @param $totalPagarExtenso
     * @param $textoHash
     * @param int $moedaId
     * @param $totalDesconto
     * @param $totalIva
     * @param int $totalMulta
     * @param $nomeCliente
     * @param $numeroCartaoCliente
     * @param $telefoneCliente
     * @param $nifCliente
     * @param $emailCliente
     * @param $enderecoCliente
     * @param $contaCorrenteCliente
     * @param $numeroItems
     * @param $tipoDocumento
     * @param $tipoFolha
     * @param $totalRetencao
     * @param $numSequenciaFatura
     * @param $numeracaoFatura
     * @param $hashValor
     * @param string $retificado
     * @param $formaPagamentoId
     * @param $armazemId
     * @param $clienteId
     * @param int $canalId
     * @param int $statusId
     * @param null $statusFactura
     * @param null $dataVencimento
     * @param $observacao
     */
    public function __construct(
        $totalPrecoFatura,
        $totalPagar,
        $totalEntregue,
        $totalMulticaixa,
        $totalCash,
        $totalTroco,
        $totalIncidencia,
        $totalPagarExtenso,
        $textoHash,
        int $moedaId,
        $totalDesconto,
        $totalIva,
        int $totalMulta,
        $nomeCliente,
        $bonus,
        $valorBonus,
        $saldoAnterior,
        $aplicadoCartaoCliente,
        $valorDescontarSaldo,
        $numeroCartaoCliente,
        $saldoCliente,
        $telefoneCliente,
        $nifCliente,
        $emailCliente,
        $enderecoCliente,
        $contaCorrenteCliente,
        $numeroItems,
        $tipoDocumento,
        $tipoFolha,
        $totalRetencao,
        $numSequenciaFatura,
        $numeracaoFatura,
        $hashValor,
        string $retificado,
        $formaPagamentoId,
        $armazemId,
        $clienteId,
        int $canalId,
        int $statusId,
        $statusFactura,
        $dataVencimento,
        $observacao,
        $isNovaEntrega
    )
    {
        $this->totalPrecoFatura = $totalPrecoFatura;
        $this->totalPagar = $totalPagar;
        $this->totalEntregue = $totalEntregue;
        $this->totalMulticaixa = $totalMulticaixa;
        $this->totalCash = $totalCash;
        $this->totalTroco = $totalTroco;
        $this->totalIncidencia = $totalIncidencia;
        $this->totalPagarExtenso = $totalPagarExtenso;
        $this->textoHash = $textoHash;
        $this->moedaId = $moedaId;
        $this->totalDesconto = $totalDesconto;
        $this->totalIva = $totalIva;
        $this->totalMulta = $totalMulta;
        $this->nomeCliente = $nomeCliente;
        $this->bonus = $bonus;
        $this->valorBonus = $valorBonus;
        $this->saldoAnterior = $saldoAnterior;
        $this->aplicadoCartaoCliente = $aplicadoCartaoCliente;
        $this->valorDescontarSaldo = $valorDescontarSaldo;
        $this->numeroCartaoCliente = $numeroCartaoCliente;
        $this->saldoCliente = $saldoCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->nifCliente = $nifCliente;
        $this->emailCliente = $emailCliente;
        $this->enderecoCliente = $enderecoCliente;
        $this->contaCorrenteCliente = $contaCorrenteCliente;
        $this->numeroItems = $numeroItems;
        $this->tipoDocumento = $tipoDocumento;
        $this->tipoFolha = $tipoFolha;
        $this->totalRetencao = $totalRetencao;
        $this->numSequenciaFatura = $numSequenciaFatura;
        $this->numeracaoFatura = $numeracaoFatura;
        $this->hashValor = $hashValor;
        $this->retificado = $retificado;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->armazemId = $armazemId;
        $this->clienteId = $clienteId;
        $this->canalId = $canalId;
        $this->statusId = $statusId;
        $this->statusFactura = $statusFactura;
        $this->dataVencimento = $dataVencimento;
        $this->observacao = $observacao;
        $this->isNovaEntrega = $isNovaEntrega;
    }

    /**
     * @return mixed
     */
    public function getTotalPrecoFatura()
    {
        return $this->totalPrecoFatura;
    }

    /**
     * @return mixed
     */
    public function getTotalPagar()
    {
        return $this->totalPagar;
    }

    /**
     * @return mixed
     */
    public function getTotalEntregue()
    {
        if ($this->getFormaPagamentoId() == 1 && !$this->totalEntregue && !$this->getAplicadoCartaoCliente()) throw new \Error("Informe o valor entregue");
        if ($this->getFormaPagamentoId() == 1 && ($this->totalEntregue < $this->getTotalPagar()) && !$this->getAplicadoCartaoCliente()) throw new \Error("Total entregue inferior ao total a pagar");
        if (($this->getFormaPagamentoId() == 1 && !$this->getTotalMulticaixa()) && $this->totalEntregue < 0) throw new \Error("Informe o valor entregue");
        if ($this->getFormaPagamentoId() == 2) return 0;
        return $this->totalEntregue;
    }

    /**
     * @return mixed
     */
    public function getTotalMulticaixa()
    {
        if ($this->getFormaPagamentoId() == 2) return 0;
        if ($this->getFormaPagamentoId() == 3) return $this->getTotalPagar();
        if ($this->totalMulticaixa < 0) throw new \Error("Informou numero abaixo de zero no valor multicaixa");
        return $this->totalMulticaixa;
    }
    public function getTotalCash()
    {
        if ($this->getFormaPagamentoId() == 2) return 0;
        return abs($this->getTotalPagar() - $this->getTotalMulticaixa());
    }
    public function getTotalTroco()
    {
        if ($this->getFormaPagamentoId() == 2) return 0;
        return $this->totalTroco;
    }
    public function getTotalIncidencia()
    {
        return $this->totalIncidencia;
    }

    /**
     * @return mixed
     */
    public function getTotalPagarExtenso()
    {
        return $this->totalPagarExtenso;
    }

    /**
     * @return mixed
     */
    public function getTextoHash()
    {
        return $this->textoHash;
    }

    /**
     * @return int
     */
    public function getMoedaId(): int
    {
        return $this->moedaId;
    }

    /**
     * @return mixed
     */
    public function getTotalDesconto()
    {
        return $this->totalDesconto;
    }

    /**
     * @return mixed
     */
    public function getTotalIva()
    {
        return $this->totalIva;
    }

    /**
     * @return int
     */
    public function getTotalMulta(): int
    {
        return $this->totalMulta;
    }

    /**
     * @return mixed
     */
    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    public function getBonus()
    {
        return $this->bonus;
    }

    public function getValorBonus()
    {
        return $this->valorBonus;
    }

    public function getSaldoAnterior()
    {
        return $this->saldoAnterior;
    }

    public function getTotalBonus()
    {
        if (!$this->getAplicadoCartaoCliente()) {
            return $this->getTotalPagar() * $this->getBonus() / 100;
        }
        return 0;
    }

    public function getSaldoAumento()
    {
        return $this->getSaldoCliente() + $this->getTotalBonus();
    }

    public function getSaldoReduzir()
    {
        return $this->getSaldoCliente() - $this->getTotalBonus();
    }

    public function getAplicadoCartaoCliente()
    {
        return $this->aplicadoCartaoCliente;
    }

    public function getValorDescontarSaldo()
    {
        return $this->valorDescontarSaldo;
    }

    public function getSaldoCliente()
    {
        return $this->saldoCliente;
    }

    /**
     * @return mixed
     */
    public function getNumeroCartaoCliente()
    {
        return $this->numeroCartaoCliente;
    }

    /**
     * @return mixed
     */
    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }

    /**
     * @return mixed
     */
    public function getNifCliente()
    {
        return $this->nifCliente;
    }

    /**
     * @return mixed
     */
    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    /**
     * @return mixed
     */
    public function getEnderecoCliente()
    {
        return $this->enderecoCliente;
    }

    /**
     * @return mixed
     */
    public function getContaCorrenteCliente()
    {
        return $this->contaCorrenteCliente;
    }

    /**
     * @return mixed
     */
    public function getNumeroItems()
    {
        return $this->numeroItems;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @return mixed
     */
    public function getTipoFolha()
    {
        return $this->tipoFolha;
    }

    /**
     * @return mixed
     */
    public function getTotalRetencao()
    {
        return $this->totalRetencao;
    }

    /**
     * @return mixed
     */
    public function getNumSequenciaFatura()
    {
        return $this->numSequenciaFatura;
    }

    /**
     * @return mixed
     */
    public function getNumeracaoFatura()
    {
        return $this->numeracaoFatura;
    }

    /**
     * @return mixed
     */
    public function getHashValor()
    {
        return $this->hashValor;
    }

    /**
     * @return string
     */
    public function getRetificado(): string
    {
        return $this->retificado;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamentoId()
    {
        if ($this->getTipoDocumento() == 3) return null;
        return $this->formaPagamentoId;
    }

    /**
     * @return mixed
     */
    public function getArmazemId()
    {
        return $this->armazemId;
    }

    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * @return int
     */
    public function getCanalId(): int
    {
        return $this->canalId;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @return null
     */
    public function getStatusFactura()
    {
        return $this->statusFactura;
    }

    /**
     * @return null
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    public function getNotaEntrega()
    {
        return isset($this->isNovaEntrega) && $this->isNovaEntrega->valor == 'sim' ? 'Y' : 'N';
    }
}
