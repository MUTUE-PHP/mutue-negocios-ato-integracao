<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;
class FaturaItemServicoComercial_
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
    private $totalServico;
    private $totalServicoOcupacaoByConsumo;
    private $taxaImpostoPredial;

    public function __construct($produtoId, $nomeProduto, $taxa, $dataEntradaEstacionamento, $dataSaidaEstacionamento, $taxaIva,$impostoPredial, $cambioDia, $considera1hDepois30min, $unidadeMetrica, $addArCondicionado, $qtdMeses, $isencaoOcupacao,$tarifaConsumo, $totalServico, $totalServicoOcupacaoByConsumo, $taxaImpostoPredial)
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
        $this->totalServico = $totalServico;
        $this->totalServicoOcupacaoByConsumo = $totalServicoOcupacaoByConsumo;
        $this->taxaImpostoPredial = $taxaImpostoPredial;
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
    public function getTarifaConsumo(){
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

        $DESCONTOARCONDICIONADO = 20;
        $DESCONTOTARIFACONSUMO = 15;
        //Serviços de ocupação
        $SERVICO_OCUPACAO_COM_ARCONDICIONADO = ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 39);
        $SERVICO_TARIFA_CONSUMO = 44;

        if ($SERVICO_OCUPACAO_COM_ARCONDICIONADO && ($this->getIsencaoOcupacao() || $this->getAddArCondicionado())) {
            $SERVICO_ARCONDICIONADO = 39;
            if ($this->getProdutoId() == $SERVICO_ARCONDICIONADO) {
                $subTotal = $this->getTotalServico() * $DESCONTOARCONDICIONADO / 100;
                $totalIva = (($subTotal * $this->getTaxaIva()) / 100) ;
                return $totalIva;

            }
            return $this->getSubtotal() + $this->getValorImpostoPredial();
        }else if($this->getTarifaConsumo() && $this->getProdutoId() == $SERVICO_TARIFA_CONSUMO){
            $subTotal = $this->getTotalServicoOcupacaoByConsumo() * $DESCONTOTARIFACONSUMO / 100;
            $totalIva = (($subTotal * $this->getTaxaIva()) / 100);
            return $totalIva;
        }
        return ($this->getSubtotalSemImposto() * $this->getTaxaIva()) / 100;
    }
    public function getValorImpostoPredial(){
        return ($this->getSubtotalSemImposto() * $this->getTaxaImpostoPredial()) / 100;
    }

    public function getTotalIva()
    {
        return $this->getSubtotalSemImposto() + $this->getValorIva();
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

    public function getTotalServico()
    {
        return $this->totalServico;
    }

    /**
     * @return mixed
     */
    public function getTotalServicoOcupacaoByConsumo()
    {
        return $this->totalServicoOcupacaoByConsumo;
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


    public function getTotal()
    {
        $DESCONTOARCONDICIONADO = 20;
        $DESCONTOTARIFACONSUMO = 15;
        //Serviços de ocupação
        $SERVICO_OCUPACAO_COM_ARCONDICIONADO = ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 39);
        $SERVICO_OCUPACAO_SEM_ARCONDICIONADO = ($this->getProdutoId() >= 28 && $this->getProdutoId() <= 38);

        $PUBLICIDADE = 40;
        $SERVICO_TARIFA_CONSUMO = 44;

        dd($SERVICO_OCUPACAO_COM_ARCONDICIONADO);

        if ($SERVICO_OCUPACAO_COM_ARCONDICIONADO && ($this->getIsencaoOcupacao() || $this->getAddArCondicionado())) {
            $SERVICO_ARCONDICIONADO = 39;
            if ($this->getProdutoId() == $SERVICO_ARCONDICIONADO) {
                $subTotal = $this->getTotalServico() * $DESCONTOARCONDICIONADO / 100;
                $totalIva = (($subTotal * $this->getTaxaIva()) / 100) ;
                $totalImpostoPredial = (($subTotal * $this->getTaxaImpostoPredial()) / 100);
                $total = $subTotal + $totalIva + $totalImpostoPredial;
                return $total;

//                return ($this->getTotalServico() * $DESCONTOARCONDICIONADO / 100) + $this->getValorIva() + $this->getValorImpostoPredial();
            }
            return $this->getSubtotal() + $this->getValorImpostoPredial();
        }else if($this->getTarifaConsumo() && $this->getProdutoId() == $SERVICO_TARIFA_CONSUMO){
            $subTotal = $this->getTotalServicoOcupacaoByConsumo() * $DESCONTOTARIFACONSUMO / 100;
//            dd($subTotal);
            $totalIva = (($subTotal * $this->getTaxaIva()) / 100);
            $totalImpostoPredial = (($subTotal * $this->getTaxaImpostoPredial()) / 100);
            $total = $subTotal + $totalIva + $totalImpostoPredial;
            return $total;
//            return $this->getTotalServicoOcupacaoByConsumo() * $DESCONTOTARIFACONSUMO / 100;
        }
        else if ($SERVICO_OCUPACAO_SEM_ARCONDICIONADO && $this->getAddArCondicionado()) {
            return $this->getSubtotal() + $this->getDesconto();
        } else if ($this->getProdutoId() == $PUBLICIDADE) {
            return $this->getTaxa() * $this->getUnidadeMetrica() * $this->getQtdMeses() * $this->getCambioDia();
        }
        //Fim Serviços de ocupação
        else {
            $estacionamentoCamiaoDentroTCA = $this->getProdutoId() == 41;
            $estacionamentoCamiaoForaTCA = $this->getProdutoId() == 42;
            $estacionamentoDeVeiculo = $this->getProdutoId() == 43;

            if ($estacionamentoCamiaoDentroTCA) {
                if ($this->conversorDeHoraParaMinuto() <= 30) {
                    return $this->getTaxaDentroDoTca0a30() * $this->getCambioDia();
                } else if ($this->conversorDeHoraParaMinuto() > 30 && $this->conversorDeHoraParaMinuto() <= 60) {
                    return $this->getTaxaDentroDoTcaAte1h() * $this->getCambioDia();
                } else if ($this->conversorDeHoraParaMinuto() > 60) {
                    $minutos = $this->conversorDeHoraParaMinuto() - 60;
                    $minutosParcelado = ceil( $minutos / 30);
                    return ($this->getTaxaDentroDoTcaAte1h() + ($minutosParcelado * $this->getTaxaDentroDoTca0a30())) * $this->getCambioDia();
                }
            }
            if ($estacionamentoCamiaoForaTCA) {
                if ($this->conversorDeHoraParaMinuto() <= 30) {
                    return $this->getTaxaForaDoTca0a30() * $this->getCambioDia();
                } else if ($this->conversorDeHoraParaMinuto() > 30 && $this->conversorDeHoraParaMinuto() <= 60) {
                    return $this->getTaxaForaDoTcaAte1h() * $this->getCambioDia();
                }else if ($this->conversorDeHoraParaMinuto() > 60) {
                    $minutos = $this->conversorDeHoraParaMinuto() - 60;
                    $minutosParcelado = ceil( $minutos / 30);
                    return ($this->getTaxaForaDoTcaAte1h() + ($minutosParcelado * $this->getTaxaForaDoTca0a30())) * $this->getCambioDia();
                }
            }
            if ($estacionamentoDeVeiculo) {
                if ($this->conversorDeHoraParaMinuto() <= 30) {
                    return $this->getTaxaVeiculo0a30() * $this->getCambioDia();
                } else if ($this->conversorDeHoraParaMinuto() > 30 && $this->conversorDeHoraParaMinuto() <= 60) {
                    return $this->getTaxaVeiculoAte1h() * $this->getCambioDia();
                }else if ($this->conversorDeHoraParaMinuto() > 60) {
                    $minutos = $this->conversorDeHoraParaMinuto() - 60;
                    $minutosParcelado = ceil( $minutos / 30);
                    return ($this->getTaxaVeiculoAte1h() + ($minutosParcelado * $this->getTaxaVeiculo0a30())) * $this->getCambioDia();
                }
            }
            return $this->getSubtotal();
        }
    }

    public function getSubtotal()
    {
        return $this->getSubtotalSemImposto();
    }


    public function getDesconto()
    {
        return $this->getSubtotalSemImposto() * $this->getValorDesc() / 100;
    }
    public function getSubtotalSemImposto(){
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
