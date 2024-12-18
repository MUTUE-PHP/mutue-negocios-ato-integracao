<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemOutroServico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaOutroServico;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Infra\Repository\Empresa\TaxaPesoMaximoDescolagemRepositoryRepository;
use Illuminate\Support\Facades\DB;

class SimuladorOutroServicoAeroporto
{

    public function __construct(RepositoryFactory $repositoryFactory){}

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

        $faturaOutroServico = new FaturaOutroServico(
            $input->tipoDocumento,
            $input->formaPagamentoId,
            $input->observacao,
            $input->nomeProprietario,
            $input->clienteId,
            $input->nomeCliente,
            $input->telefoneCliente,
            $input->nifCliente,
            $input->emailCliente,
            $input->enderecoCliente,
            $taxaIva,
            $moedaEstrageiraUsado,
            $input->moedaPagamento,
            $input->isencaoIVA,
            $input->retencao,
            $valorRetencao,
            $cambioDia
        );
        foreach ($input->items as $item) {
            $item = (object)$item;
            $faturaItemOutroServico = new FaturaItemOutroServico(
                $item->produtoId,
                $item->nomeProduto,
                $item->desconto,
                $item->precoVenda,
                $taxaIva,
                $item->quantidade,
                $cambioDia
            );

            $faturaOutroServico->addItem($faturaItemOutroServico);
        }
        return $faturaOutroServico;
    }
}

