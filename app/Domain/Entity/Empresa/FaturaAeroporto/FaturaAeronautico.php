<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;

class FaturaAeronautico
{

    private $tipoDocumento;
    private $formaPagamentoId;
    private $observacao;
    private $nomeProprietario;
    private $clienteId;
    private $nomeCliente;
    private $telefoneCliente;
    private $nifCliente;
    private $emailCliente;
    private $enderecoCliente;
    private $tipoDeAeronave;
    private $horaMangaAcoplagem;
    private $horaMangaDesacoplagem;
    private $numeroAeronaveChegada;
    private $numeroAeronavePartida;
    private $pesoMaximoDescolagem;
    private $dataDeAterragem;
    private $dataDeDescolagem;
    private $horaDeAterragem;
    private $horaDeDescolagem;
    private $taxaIva;
    private $peso;
    private $totalPeso = 0;
    private $horaExtra;
    private $cambioDia;
    private $moeda;
    private $moedaPagamento;
    private $considera1hDepois14min;
    private $items = [];


    public function __construct($tipoDocumento, $formaPagamentoId, $observacao, $isencaoIVA, $retencao, $valorRetencao, $nomeProprietario, $clienteId, $nomeCliente, $telefoneCliente, $nifCliente, $emailCliente, $enderecoCliente, $tipoDeAeronave,$numeroAeronaveChegada,$numeroAeronavePartida,$horaMangaAcoplagem, $horaMangaDesacoplagem, $pesoMaximoDescolagem, $dataDeAterragem, $dataDeDescolagem, $horaDeAterragem, $horaDeDescolagem, $taxaIva, $peso, $horaExtra, $cambioDia, $moeda, $moedaPagamento, $considera1hDepois14min)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->observacao = $observacao;
        $this->isencaoIVA = $isencaoIVA;
        $this->retencao = $retencao;
        $this->valorRetencao = $valorRetencao;
        $this->nomeProprietario = $nomeProprietario;
        $this->clienteId = $clienteId;
        $this->nomeCliente = $nomeCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->nifCliente = $nifCliente;
        $this->emailCliente = $emailCliente;
        $this->enderecoCliente = $enderecoCliente;
        $this->tipoDeAeronave = $tipoDeAeronave;
        $this->numeroAeronaveChegada = $numeroAeronaveChegada;
        $this->numeroAeronavePartida = $numeroAeronavePartida;
        $this->horaMangaAcoplagem = $horaMangaAcoplagem;
        $this->horaMangaDesacoplagem = $horaMangaDesacoplagem;
        $this->pesoMaximoDescolagem = $pesoMaximoDescolagem;
        $this->dataDeAterragem = $dataDeAterragem;
        $this->dataDeDescolagem = $dataDeDescolagem;
        $this->horaDeAterragem = $horaDeAterragem;
        $this->horaDeDescolagem = $horaDeDescolagem;
        $this->taxaIva = $taxaIva;
        $this->peso = $peso;
        $this->horaExtra = $horaExtra;
        $this->cambioDia = $cambioDia;
        $this->moeda = $moeda;
        $this->moedaPagamento = $moedaPagamento;
        $this->considera1hDepois14min = $considera1hDepois14min;
    }

    public function addItem(FaturaItemAeronautico $items)
    {
        $this->items[] = $items;
    }

    public function getMoeda()
    {
        return $this->moeda;
    }
    public function getConsidera1hDepois14min()
    {
        return $this->considera1hDepois14min;
    }

    public function getMoedaPagamento()
    {
        return $this->moedaPagamento;
    }


    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamentoId()
    {
        if ($this->getTipoDocumento() == 3) return null;
        if ($this->getTipoDocumento() == 2) return 2;
        return $this->formaPagamentoId;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @return mixed
     */
    public function getIsencaoIVA()
    {
        return $this->isencaoIVA;
    }

    /**
     * @return mixed
     */
    public function getRetencao()
    {
        return $this->retencao;
    }

    /**
     * @return mixed
     */
    public function getValorRetencao()
    {
        return ($this->getValorIliquido() * $this->getTaxaRetencao()) / 100;

    }

    public function getTaxaRetencao()
    {
        return $this->valorRetencao;
    }


    public function getProprietario()
    {
        return $this->nomeProprietario;
    }

    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }

    public function getNifCliente()
    {
        return $this->nifCliente;
    }

    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    public function getEnderecoCliente()
    {
        return $this->enderecoCliente;
    }

    public function getTipoDeAeronave()
    {
        return $this->tipoDeAeronave;
    }
    public function getNumeroAeronaveChegada(){
        return $this->numeroAeronaveChegada;
    }
    public function getNumeroAeronavePartida(){
        return $this->numeroAeronavePartida;
    }
    public function getHoraMangaAcoplagem(){
        return $this->horaMangaAcoplagem;
    }
    public function getHoraMangaDesacoplagem(){
        return $this->horaMangaDesacoplagem;
    }
    public function getPesoMaximoDescolagem()
    {
        return $this->pesoMaximoDescolagem;
    }

    /**
     * @return mixed
     */
    public function getDataDeAterragem()
    {
        return $this->dataDeAterragem;
    }

    /**
     * @return mixed
     */
    public function getDataDeDescolagem()
    {
        return $this->dataDeDescolagem;
    }

    /**
     * @return mixed
     */
    public function getHoraDeAterragem()
    {
        return $this->horaDeAterragem;
    }
    public function getHoraMangaAcoplagemEDesacoplagem()
    {
        $dataInicial = $this->getHoraMangaAcoplagem();
        $dataFinal = $this->getHoraMangaDesacoplagem();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        if($hora1 > $hora2) throw  new \Error("Hora incorreto");
        $diff = $hora1->diff($hora2);

        $horas = $diff->h + $diff->days * 24;
        if ($diff->i >= 1) {
            $horas = ++$horas;
        }
        return $horas;
    }

    public function getHoraEstacionamento()
    {
        $dataInicial = $this->getDataDeAterragem() . " " . $this->getHoraDeAterragem();
        $dataFinal = $this->getDataDeDescolagem() . " " . $this->getHoraDeDescolagem();
        $hora1 = new \DateTime($dataInicial);
        $hora2 = new \DateTime($dataFinal);
        $diff = $hora1->diff($hora2);
        $horas = $diff->h + $diff->days * 24;
        if ($this->getConsidera1hDepois14min() == 'SIM' && $diff->i > 14) {
            $horas = ++$horas;
        }
        return $horas;
    }

    public function getHoraDeDescolagem()
    {
        return $this->horaDeDescolagem;
    }

    /**
     * @return mixed
     */
    public function getTaxaIva()
    {
        return $this->taxaIva;
    }

    public function getHoraExtra()
    {
        return $this->horaExtra;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso){
        $this->peso = $peso;
    }
    public function setPesoTotal($peso){
        $this->totalPeso = $peso;
    }
    public function getPesoTotal(){
        return $this->totalPeso;
    }


    /**
     * @return mixed
     */
    public function getCambioDia()
    {
        return $this->cambioDia;
    }

    public function getContraValor()
    {
        return $this->getTotal() / $this->getCambioDia();
    }

    public function getItems(): array
    {
        return $this->items;
    }
    public function getValorLiquido(){
        return $this->getDesconto() + $this->getValorIliquido();
    }
    public function getDesconto(){
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getValorDesconto();
        }
        return $total;
    }

    public function getValorIliquido()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }
        $total = $total - $this->getDesconto();
        return $total;
    }

    public function getValorImposto()
    {
        return ($this->getValorIliquido() * $this->getTaxaIva()) / 100;
    }

    public function getTotal()
    {
        return $this->getValorIliquido() + $this->getValorImposto() - $this->getValorRetencao();
    }


}
