<?php

namespace App\Domain\Entity\Admin;
use Keygen\Keygen;
use NumberFormatter;

class FaturaLicenca
{
    private Licenca $licenca;
    private Empresa $empresa;
    private $observacao;
    private $numSequencia;
    private $numeracaoFatura;
    private $hashValor;
    private $formaPagamento;
    private $quantidade;

    public function __construct(Licenca $licenca, Empresa $empresa, FormaPagamento $formaPagamento, $numSequencia, $numeracaoFatura, $hashValor, $observacao, $quantidade)
    {
        $this->licenca = $licenca;
        $this->empresa = $empresa;
        $this->observacao = $observacao;
        $this->numSequencia = $numSequencia;
        $this->numeracaoFatura = $numeracaoFatura;
        $this->hashValor = $hashValor;
        $this->formaPagamento = $formaPagamento;
        $this->quantidade = $quantidade;
    }
    public function getQuantidade(){
        return $this->quantidade;
    }
    public function getTotalPrecoFatura(){
        return $this->getLicenca()->getValor() * $this->getQuantidade();
    }
    public function getTotalEntregue(){
        return 0;
    }
    public function getTotalPagar(){
        return $this->getLicenca()->getValor() * $this->getQuantidade();
    }
    public function getTotalTroco(){
        return 0;
    }
    public function getMoedaId(){
        return 1;
    }
    public function getTotalDesconto(){
        return 0;
    }
    public function getTotalIva(){
        return $this->licenca->getValorTaxaIva();
    }
    public function getTotalSemImposto(){
        return $this->getTotalPrecoFatura() - $this->getTotalIva();
    }
    public function getTotalMulta(){
        return 0;
    }
    public function getTotalRetencao(){
        return 0;
    }
    public function getNumeroSequencia(){
        return $this->numSequencia;
    }
    public function getNumeracaoFatura(){
        return $this->numeracaoFatura;
    }
    public function getNumeroItems(){
        return 1;
    }
    public function getTipoFolha(){
        return 'A4';
    }
    public function getValorHash(){
        return $this->hashValor;
    }
    public function getTotalPorExtenso(){
        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);
        return $f->format($this->getTotalPrecoFatura() ?? 0);
    }
    public function getLicenca(): Licenca
    {
        return $this->licenca;
    }
    public function getEmpresa(): Empresa
    {
        return $this->empresa;
    }
    public function getFormaPagamento(): FormaPagamento
    {
        return $this->formaPagamento;
    }
    public function getObservacao(){
        return $this->observacao;
    }
    public function getStatusId(){
        return 1;
    }
    public function getCanalId(){
        return 2;
    }
    public function getFaturaReferencia(){
        return mb_strtoupper(Keygen::numeric(9)->generate());
    }
}
