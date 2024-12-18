<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemCarga;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\EspecificacaoMercadoriaRepository;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Infra\Repository\Empresa\TipoMercadoriaRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class SimuladorFaturaCargaAeroporto
{
    private EspecificacaoMercadoriaRepository $especificacaoMercadoriaRepository;
    private TaxaCargaAduaneiraRepository $taxaCargaAduaneiraRepository;
    private TipoMercadoriaRepository $taxaTipoMercadoriaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->especificacaoMercadoriaRepository = $repositoryFactory->createEspecificacaoMercadoriaRepository();
        $this->taxaCargaAduaneiraRepository = $repositoryFactory->createTaxaCargaAduaneiraRepository();
        $this->taxaTipoMercadoriaRepository = $repositoryFactory->createTipoMercadoriaRepository();
    }

    public function execute($input)
    {
        $input = (object)$input;
        if ($input->isencaoIVA) {
            $taxaIva = 0;
        } else {
            $taxaIva = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $taxaIva = (float)$taxaIva->execute('valor_iva_aplicado')->valor;
        }

        if ($input->retencao) {
            $retencaoFonte = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $valorRetencao = (float)$retencaoFonte->execute('valor_retencao_fonte')->valor;
        } else {
            $valorRetencao = 0;
        }
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $moedaEstrageiraUsado = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;
        $cambioDia = DB::table('cambios')->where('designacao', $moedaEstrageiraUsado)->first()->valor;

        $faturaCarga = new FaturaCarga(
            $input->cartaDePorte,
            $input->tipoDocumento,
            $input->tipoOperacao,
            $input->formaPagamentoId,
            $input->isencaoIVA,
            $input->isencaoCargaTransito,
            $input->retencao,
            $valorRetencao,
            $input->clienteId,
            $input->nomeCliente ?? null,
            $input->nomeProprietario ?? null,
            $input->telefoneCliente,
            $input->nifCliente,
            $input->emailCliente,
            $input->enderecoCliente,
            $input->peso,
            $input->dataEntrada,
            $input->dataSaida,
            $input->nDias,
            $taxaIva,
            $cambioDia,
            $moedaEstrageiraUsado,
            $input->moedaPagamento,
            $input->observacao
        );
        foreach ($input->items as $item) {
            $item = (object)$item;
            if ($item->produtoId == 1) {//Produto/Serviçso do tipo Carga
                $taxaCargaAduaneira = $this->taxaCargaAduaneiraRepository->getTaxaCargaById($item->sujeitoDespachoId);
                $taxa = new \App\Domain\Entity\Empresa\Taxa(
                    $taxaCargaAduaneira->designacao,
                    $taxaCargaAduaneira->taxa,
                    0
                );
            } else {// Todos Produtos/Serviçso diferentes de Carga
                $taxaTipoMercadoria = $this->taxaTipoMercadoriaRepository->getTipoMercadoria($item->tipoMercadoriaId);
                $espeficificaoMercadoria = $this->especificacaoMercadoriaRepository->getEspecificacaoMercadoriaById($item->especificacaoMercadoriaId);
                $desconto = $espeficificaoMercadoria->desconto;
                if ($item->produtoId == 3) { //Produto/Serviçso do tipo Manuseamento
                    $desconto = 0;
                }
                $taxa = new \App\Domain\Entity\Empresa\Taxa(
                    $taxaTipoMercadoria->designacao,
                    $taxaTipoMercadoria->taxa,
                    $desconto
                );
            }
            $faturaItemCarga = new FaturaItemCarga(
                $item->produtoId,
                $item->nomeProduto,
                $item->desconto,
                $taxa,
                $taxaIva,
                $faturaCarga->getPeso(),
                $faturaCarga->getNDias(),
                $cambioDia,
                $item->sujeitoDespachoId,
                $item->tipoMercadoriaId,
                $item->especificacaoMercadoriaId,
                $input->dataEntrada,
                $input->dataSaida,
                $input->tipoOperacao,
                $input->isencaoCargaTransito
            );
            $faturaCarga->addItem($faturaItemCarga);
        }
        return $faturaCarga;
    }

}

