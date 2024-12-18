<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemServicoComercial;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaServicoComercial;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Infra\Repository\Empresa\TaxaPesoMaximoDescolagemRepositoryRepository;
use Illuminate\Support\Facades\DB;

class SimuladorFaturaServicoComercial
{
    private TaxaPesoMaximoDescolagemRepositoryRepository $taxaPesoMaximoDescolagemRepository;

    private TaxaCargaAduaneiraRepository $taxaCargaAduaneiraRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->taxaPesoMaximoDescolagemRepository = $repositoryFactory->createTaxaPesoMaximoDescolagemRepositoryRepository();
        $this->taxaCargaAduaneiraRepository = $repositoryFactory->createTaxaCargaAduaneiraRepository();

    }

    public function conversorHora($string)
    {
        $data = getDate(strtotime($string));
        if ($data['minutes'] > 14) {
            return ++$data['hours'];
        }
        return $data['hours'];
    }

    public function execute($input, $items = [])
    {
        $input = (object)$input;
        if ($input->isencaoIVA) {
            $taxaIva = 0;
        } else {
            $taxaIva = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $taxaIva = (float)$taxaIva->execute('valor_iva_aplicado')->valor;
        }

        $taxaImpostoPredial = DB::table('parametros')
            ->where('empresa_id', 1)->where('label', 'valor_imposto_predial_aplicado')->first()->valor;

        if ($input->retencao) {
            $retencaoFonte = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $valorRetencao = (float)$retencaoFonte->execute('valor_retencao_fonte')->valor;
        } else {
            $valorRetencao = 0;
        }
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $moedaEstrageiraUsado = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;
        $cambioDia = DB::table('cambios')->where('designacao', $moedaEstrageiraUsado)->first()->valor;
        $considera1hDepois30min = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $considera1hDepois30min = $considera1hDepois30min->execute('considerar1hdepois30min')->valor;

        $faturaServicoComercial = new FaturaServicoComercial(
            $input->dataEntradaEstacionamento,
            $input->dataSaidaEstacionamento,
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
            $taxaImpostoPredial,
            $cambioDia,
            $moedaEstrageiraUsado,
            $input->moedaPagamento,
            $input->isencaoIVA,
            $input->retencao,
            $valorRetencao,
            $input->unidadeMetrica,
            $input->addArCondicionado,
            $input->qtdMeses,
            $input->isencaoOcupacao,
            $input->tarifaConsumo
        );

        $totalTarifaOcupacao = $this->getTotalTarifaOcupacao($input->items, $cambioDia);

        foreach ($input->items as $itemData) {
            $item = (object)$itemData;
            $taxaIva = $item->taxaIva;
            if ($input->isencaoIVA) {
                $taxaIva = 0;
            }

            $faturaItem = new FaturaItemServicoComercial(
                $item->produtoId,
                $item->nomeProduto,
                $item->taxa,
                $input->dataEntradaEstacionamento,
                $input->dataSaidaEstacionamento,
                $taxaIva,
                $item->impostoPredial,
                $cambioDia,
                $considera1hDepois30min,
                $item->unidadeMetrica,
                $item->addArCondicionado,
                $item->qtdMeses,
                $input->isencaoOcupacao,
                $input->tarifaConsumo,
                $taxaImpostoPredial,
                $totalTarifaOcupacao
            );
            $faturaServicoComercial->addItem($faturaItem);
        }
        return $faturaServicoComercial;
    }

    public function getTotalTarifaOcupacao($items, $cambio)
    {
        $total = 0;
        foreach ($items as $item) {
            if ($this->isServicoOcupacao($item)) {
                $total += $item['taxa'] * $item['unidadeMetrica'] * $item['qtdMeses'] * $cambio;
            }
        }
        return $total;
    }

    public function isServicoOcupacao($item)
    {
        return ($item['produtoId'] >= 28 && $item['produtoId'] <= 38);
    }
}
