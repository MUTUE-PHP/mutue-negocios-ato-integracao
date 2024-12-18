<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;
class FaturaItemServicoComercial
{
    private $produtoId;
    private $nomeProduto;
    private $horaEstacionamento;
    private $dataEntradaEstacionamento;
    private $dataSaidaEstacionamento;
    private $taxaIva;
    private $impostoPredial;
    private $cambioDia;
    private $considera1hDepois30min;
    private $unidadeMetrica;
    private $taxa;
    private $addArCondicionado;
    private $qtdMeses;
    private $isencaoOcupacao;
    private $tarifaConsumo;
    private $totalTarifaOcupacao;
    private $taxaImpostoPredial;
    private $totalTarifaItem;

    public function __construct($produtoId, $nomeProduto, $taxa, $dataEntradaEstacionamento, $dataSaidaEstacionamento, $taxaIva, $impostoPredial, $cambioDia, $considera1hDepois30min, $unidadeMetrica, $addArCondicionado, $qtdMeses, $isencaoOcupacao, $tarifaConsumo, $taxaImpostoPredial, $totalTarifaOcupacao)
    {
        $this->produtoId = $produtoId;
        $this->nomeProduto = $nomeProduto;
        $this->taxa = $taxa;
        $this->dataEntradaEstacionamento = $dataEntradaEstacionamento;
        $this->dataSaidaEstacionamento = $dataSaidaEstacionamento;
        $this->taxaIva = $taxaIva;
        $this->impostoPredial = $impostoPredial;
        $this->cambioDia = $cambioDia;
        $this->considera1hDepois30min = $considera1hDepois30min;
        $this->unidadeMetrica = $unidadeMetrica;
        $this->addArCondicionado = $addArCondicionado;
        $this->qtdMeses = $qtdMeses;
        $this->isencaoOcupacao = $isencaoOcupacao;
        $this->tarifaConsumo = $tarifaConsumo;
        $this->taxaImpostoPredial = $taxaImpostoPredial;
        $this->totalTarifaOcupacao = $totalTarifaOcupacao;
        $this->getTotal();

    }

    public function getProdutoId()
    {
        return $this->produtoId;
    }

    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }

    public function getTaxa()
    {
        return $this->taxa;
    }

    public function getUnidadeMetrica()
    {
        return (float)$this->unidadeMetrica;
    }

    public function getAddArCondicionado()
    {
        return $this->addArCondicionado;
    }

    public function getIsencaoOcupacao()
    {
        return $this->isencaoOcupacao;
    }

    public function getTarifaConsumo()
    {
        return $this->tarifaConsumo;
    }

    public function getQtdMeses()
    {
        $SERVICO_OCUPACAO = (($this->getProdutoId() >= 28 && $this->getProdutoId() <= 38) || $this->getProdutoId() == 40);
        $SERVICO_ARCONDICIONADO = 39;
        if ($SERVICO_OCUPACAO || ($this->getProdutoId() == $SERVICO_ARCONDICIONADO)) {
            return $this->qtdMeses;
        }
        return null;
    }

    public function getValorDesc()
    {
        $SERVICO_OCUPACAO = ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 38);
        if ($this->getIsencaoOcupacao() && $SERVICO_OCUPACAO) {
            return 100;
        }
        return 0;
    }

    public function getDataEntradaEstacionamento()
    {
        return $this->dataEntradaEstacionamento;
    }

    public function getDataSaidaEstacionamento()
    {
        return $this->dataSaidaEstacionamento;
    }

    public function getTaxaIva()
    {
        return $this->taxaIva;
    }

    public function getTaxaImpostoPredial()
    {
        return $this->impostoPredial;
    }


    public function getCambioDia()
    {
        return $this->cambioDia;
    }

    public function getConsidera1hDepois30min()
    {
        return $this->considera1hDepois30min;
    }

    public function getValorIva()
    {
        return ($this->getTotal() * $this->getTaxaIva()) / 100;
//        $DESCONTOARCONDICIONADO = 20;
//        $DESCONTOTARIFACONSUMO = 15;
//        //Serviços de ocupação
//        $SERVICO_OCUPACAO_COM_ARCONDICIONADO = ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 39);
//        $SERVICO_TARIFA_CONSUMO = 44;
//
//        if ($SERVICO_OCUPACAO_COM_ARCONDICIONADO && ($this->getIsencaoOcupacao() || $this->getAddArCondicionado())) {
//            $SERVICO_ARCONDICIONADO = 39;
//            if ($this->getProdutoId() == $SERVICO_ARCONDICIONADO) {
//                $subTotal = $this->getTotalServico() * $DESCONTOARCONDICIONADO / 100;
//                $totalIva = (($subTotal * $this->getTaxaIva()) / 100);
//                return $totalIva;
//            }
//            return $this->getSubtotal() + $this->getValorImpostoPredial();
//        } else if ($this->getTarifaConsumo() && $this->getProdutoId() == $SERVICO_TARIFA_CONSUMO) {
//            $subTotal = $this->getTotalServicoOcupacaoByConsumo() * $DESCONTOTARIFACONSUMO / 100;
//            $totalIva = (($subTotal * $this->getTaxaIva()) / 100);
//            return $totalIva;
//        }
//        return ($this->getSubtotalSemImposto() * $this->getTaxaIva()) / 100;
    }


    public function getTotalIva()
    {
        return ($this->getTotalTarifaItem() * $this->getTaxaIva()) / 100;
    }
    public function getValorTotalComImpostos(){

        //return $this->getTotalTarifaItem();
      //  dd($this->getTotalTarifaItem());
//        $totalImpostoPredial = $this->getValorImpostoPredial();
        $totalIva = $this->getTotalIva();
//        $totalDesconto = $this->getDesconto();
       //return $this->getTotalTarifaItem() + $totalImpostoPredial + $totalIva;
       return $this->getTotalTarifaItem() + $totalIva;
    }

    public function getDescHoraEstacionamento()
    {
        return $this->getHoraEstacionamento() . "h:" . $this->getMinutoEstacionamento() . "min";
//        return $this->getDiasEstacionamento() . " dias/" . $this->getHoraEstacionamento() . "h:" . $this->getMinutoEstacionamento() . "min";
    }

    public function getHoraEstacionamento()
    {
        $dataInicial = $this->getDataEntradaEstacionamento();
        $dataFinal = $this->getDataSaidaEstacionamento();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        $diff = $hora1->diff($hora2);
        $horas = $diff->h + $diff->days * 24;
//        if ($this->getConsidera1hDepois30min() == 'SIM' && $diff->i > 30) {
//            $horas = ++$horas;
//        }
        return $horas;
    }

    public function converterDiasByHoras($dias)
    {
        return $dias * 24;
    }

    public function getDiasEstacionamento()
    {
        $dataInicial = $this->getDataEntradaEstacionamento();
        $dataFinal = $this->getDataSaidaEstacionamento();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        $diff = $hora1->diff($hora2);
        return $diff->days;
    }

    public function getMinutoEstacionamento()
    {
        $dataInicial = $this->getDataEntradaEstacionamento();
        $dataFinal = $this->getDataSaidaEstacionamento();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        $diff = $hora1->diff($hora2);
        return $diff->i;
    }


    /**
     * @return mixed
     */
    public function getTotalTarifaOcupacao()
    {
        return $this->totalTarifaOcupacao;
    }
    public function taxaServicoEstacionamentoDentroTerminal(){
        return 100;
    }

    function conversorDeHoraParaMinuto()
    {
        $dataInicial = $this->getDataEntradaEstacionamento();
        $dataFinal = $this->getDataSaidaEstacionamento();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        $diff = $hora1->diff($hora2);
        $horas = $diff->h + $diff->days * 24;
        return ($horas * 60) + $diff->i;
    }

    function conversorDeHoraParaMin($horas, $minutos)
    {
        return ($horas * 60) + $minutos;
    }

    public function isServicoOcupacao()
    {
        return ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 38);
    }

    public function isServicoPublicidade()
    {
        return $this->getProdutoId() == 40;
    }

    public function isEstacionamentoCamiaoDentroTCA()
    {
        return $this->getProdutoId() == 41;
    }

    public function isEstacionamentoCamiaoForaTCA()
    {
        return $this->getProdutoId() == 42;
    }

    public function isEstacionamentoDeVeiculo()
    {
        return $this->getProdutoId() == 43;
    }

    public function isTarifaConsumo()
    {
        return $this->getProdutoId() == 44;
    }

    public function isTarifaArCondicionado()
    {
        return $this->getProdutoId() == 39;
    }

    public function getTaxaTarifaConsumo()
    {
        return 15;
    }

    public function getTaxaTarifaArcondicionado()
    {
        return 20;
    }

    public function getTotalTarifaConsumo()
    {
        return ($this->getTotalTarifaOcupacao() * $this->getTaxaTarifaConsumo()) / 100;
    }

    public function getTotalTarifaArcondicionado()
    {
        return ($this->getTotalTarifaOcupacao() * $this->getTaxaTarifaArcondicionado()) / 100;
    }

    public function getValorImpostoPredial()
    {
        return ($this->getTotalTarifaItem() * $this->getTaxaImpostoPredial()) / 100;
    }

    public function getTotalTarifaItem()
    {
        return $this->totalTarifaItem;
    }

    public function getTotal()
    {
        $totalTarifaOcupacao = $this->getTotalTarifaOcupacao();
        if ($this->isServicoOcupacao()) {
            $this->totalTarifaItem = $totalTarifaOcupacao;
            return $totalTarifaOcupacao;
        } else if ($this->isTarifaConsumo()) {
            $totalTarifaConsumo = $this->getTotalTarifaConsumo();
            $this->totalTarifaItem = $totalTarifaConsumo;
            return $totalTarifaConsumo;
        } else if ($this->isTarifaArCondicionado()) {
            $totalTarifaArcondicionado = $this->getTotalTarifaArcondicionado();
            $this->totalTarifaItem = $totalTarifaArcondicionado;
            return $totalTarifaArcondicionado;
        } else if ($this->isServicoPublicidade()) {
            $totalPublicidade = $this->getTaxa() * $this->getUnidadeMetrica() * $this->getQtdMeses() * $this->getCambioDia();
            $this->totalTarifaItem = $totalPublicidade;
            return $totalPublicidade;
        } else if ($this->isEstacionamentoCamiaoDentroTCA()) {
            $this->totalTarifaItem = $this->taxaServicoEstacionamentoDentroTerminal() * $this->getCambioDia();
            return $this->totalTarifaItem;
        } else if ($this->isEstacionamentoCamiaoForaTCA()) {
            if ($this->conversorDeHoraParaMinuto() <= 30) {
                $totalTarifaItem =  $this->getTaxaDentroDoTca0a30() * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            } else if ($this->conversorDeHoraParaMinuto() > 30 && $this->conversorDeHoraParaMinuto() <= 60) {
                $totalTarifaItem = $this->getTaxaDentroDoTcaAte1h() * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            } else if ($this->conversorDeHoraParaMinuto() > 60) {
                $minutos = $this->conversorDeHoraParaMinuto() - 60;
                $minutosParcelado = ceil($minutos / 30);
                $totalTarifaItem =  ($this->getTaxaDentroDoTcaAte1h() + ($minutosParcelado * $this->getTaxaDentroDoTca0a30())) * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            }
        } else if ($this->isEstacionamentoDeVeiculo()) {
            if ($this->conversorDeHoraParaMinuto() <= 30) {
                $totalTarifaItem =  $this->getTaxaVeiculo0a30() * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            } else if ($this->conversorDeHoraParaMinuto() > 30 && $this->conversorDeHoraParaMinuto() <= 60) {
                $totalTarifaItem =  $this->getTaxaVeiculoAte1h() * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            } else if ($this->conversorDeHoraParaMinuto() > 60) {
                $minutos = $this->conversorDeHoraParaMinuto() - 60;
                $minutosParcelado = ceil($minutos / 30);
                $totalTarifaItem =  ($this->getTaxaVeiculoAte1h() + ($minutosParcelado * $this->getTaxaVeiculo0a30())) * $this->getCambioDia();
                $this->totalTarifaItem = $totalTarifaItem;
                return $totalTarifaItem;
            }
        }
    }

    public function getSubtotal()
    {
        return $this->getSubtotalSemImposto();
    }


    public function getDesconto()
    {
        return $this->getTotal() * $this->getValorDesc() / 100;
    }

    public function getSubtotalSemImposto()
    {
        return $this->getTaxa() * $this->getUnidadeMetrica() * $this->getQtdMeses() * $this->getCambioDia();
    }

    public function getTaxaDentroDoTca0a30()
    {
        return 0.79;
    }

    public function getTaxaDentroDoTcaAte1h()
    {
        return 1.00;

    }

    public function getTaxaDentroDoTcaDepois1hCada30min()
    {
        return 0.79;
    }

    //Taxas de estacionamento de camião fora TCA
    public function getTaxaForaDoTca0a30()
    {
        return 0.59;
    }

    public function getTaxaForaDoTcaAte1h()
    {
        return 0.88;

    }

    public function getTaxaForaDoTcaDepois1hCada30min()
    {
        return 0.59;
    }

    //Taxas de estacionamento de veiculos
    public function getTaxaVeiculo0a30()
    {
        return 0.59;
    }

    public function getTaxaVeiculoAte1h()
    {
        return 0.88;

    }

    public function getTaxaVeiculoDepois1hCada30min()
    {
        return 0.59;
    }
}
