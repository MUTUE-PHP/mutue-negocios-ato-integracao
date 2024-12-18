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

class SimuladorFaturaServicoComercial_
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
        $totalServicoComArCondicionado = 0;
        $totalServicoOcupacaoByConsumo = 0;
//        if ($input->isencaoOcupacao || $input->addArCondicionado) {

        $totalServicoDefault = 0;

        foreach ($input->items as $itemData) {
            $item = (object)$itemData;
            $taxaIva = $item->taxaIva;
            if ($taxaIva) {
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
                $totalServicoDefault,
                $totalServicoOcupacaoByConsumo,
                $taxaImpostoPredial
            );
            $SERVICO_OCUPACAO = ($item->produtoId >= 28 && $item->produtoId <= 38);
            $SERVICOS_GABINETE = ($item->produtoId == 30 || $item->produtoId == 32 || $item->produtoId == 34 || $item->produtoId == 37);

            if ($SERVICOS_GABINETE) {
                $totalServicoOcupacaoByConsumo += $faturaItem->getSubtotalSemImposto();
            }
            if (($item->addArCondicionado || $input->isencaoOcupacao) && $SERVICO_OCUPACAO) {
                $totalServicoComArCondicionado += $faturaItem->getSubtotalSemImposto();
            }
        }
        foreach ($input->items as $item) {
            $item = (object)$item;
            $faturaItem = new FaturaItemServicoComercial(
                $item->produtoId,
                $item->nomeProduto,
                $item->taxa,
                $item->dataEntradaEstacionamento ?? null,
                $item->dataSaidaEstacionamento ?? null,
                $item->taxaIva,
                $item->impostoPredial,
                $cambioDia,
                $considera1hDepois30min,
                $item->unidadeMetrica,
                $item->addArCondicionado,
                $item->qtdMeses,
                $input->isencaoOcupacao,
                $input->tarifaConsumo,
                $totalServicoComArCondicionado,
                $totalServicoOcupacaoByConsumo,
                $taxaImpostoPredial
            );
            $faturaServicoComercial->addItem($faturaItem);
        }
        return $faturaServicoComercial;

//        $getTaxaEstacionameno = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $taxaEstacionamento = (float)$getTaxaEstacionameno->execute('tarifa_estacionamento')->valor;
//
//
//        $getTaxaLuminosa = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $taxaLuminosa = (float)$getTaxaLuminosa->execute('tarifa_luminosa')->valor;
//
//        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $moedaEstrageiraUsado = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;
//        $cambioDia = DB::table('cambios')->where('designacao', $moedaEstrageiraUsado)->first()->valor;
//
//        $getHoraAberturaAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $horaAberturaAeroporto = $getHoraAberturaAeroporto->execute('hora_abertura_aeroporto')->valor;
//
//        $getHoraAberturaAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $horaFechoAeroporto = $getHoraAberturaAeroporto->execute('hora_fecho_aeroporto')->valor;
//
//        $getTaxaAbertoAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $taxaAbertoAeroporto = $getTaxaAbertoAeroporto->execute('tarifa_abertura_aeroporto')->valor;
//
//        $considera1hDepois14min = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $considera1hDepois14min = $considera1hDepois14min->execute('considerar1hdepois14min')->valor;
//
//        $getTaxaReaberturaComercial = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $taxaReaberturaComercial = (float)$getTaxaReaberturaComercial->execute('tarifa_reabertura_comercial')->valor;
//
//        $horaAberturaAeroporto = $this->conversorHora($horaAberturaAeroporto);
//        $horaFechoAeroporto = $this->conversorHora($horaFechoAeroporto);
//
//        $faturaAeronautico = new FaturaAeronautico(
//            $input->tipoDocumento,
//            $input->formaPagamentoId,
//            $input->observacao,
//            $input->isencaoIVA,
//            $input->retencao,
//            $valorRetencao,
//            $input->nomeProprietario,
//            $input->clienteId,
//            $input->nomeCliente,
//            $input->telefoneCliente,
//            $input->nifCliente,
//            $input->emailCliente,
//            $input->enderecoCliente,
//            $input->tipoDeAeronave,
//            $input->pesoMaximoDescolagem,
//            $input->dataDeAterragem,
//            $input->dataDeDescolagem,
//            $input->horaDeAterragem,
//            $input->horaDeDescolagem,
//            $taxaIva,
//            $input->peso,
//            $input->horaExtra,
//            $cambioDia,
//            $moedaEstrageiraUsado,
//            $input->moedaPagamento,
//            $considera1hDepois14min
//        );
//        $peso = 0;
//        foreach ($input->items as $item) {
//            $item = (object)$item;
//            if ($item->produtoId == 12 || $item->produtoId == 13) {
//                $peso += $item->peso;
//                $faturaAeronautico->setPeso($item->peso);
//                $faturaAeronautico->setPesoTotal($peso);
//            }
//            if ($item->produtoId == 7) {
//                $faturaAeronautico->setPesoTotal($item->peso);
//            }
//            if ($item->produtoId == 7 || $item->produtoId == 12 || $item->produtoId == 13) {
//                $taxaAduaneiraData = $this->taxaCargaAduaneiraRepository->getTaxaCargaById($item->sujeitoDespachoId);
//                $taxaAduaneira = $taxaAduaneiraData['taxa'];
//                $sujeitoDespachoId = $taxaAduaneiraData['id'];
//            } else {
//                $taxaAduaneira = 0;
//                $sujeitoDespachoId = null;
//            }
//            $faturaItemAeronautico = new FaturaItemAeronautico(
//                $item->produtoId,
//                $item->nomeProduto,
//                $input->pesoMaximoDescolagem,
//                $faturaAeronautico->getHoraEstacionamento(),
//                $taxaEstacionamento,
//                $taxaLuminosa,
//                $taxaAduaneira,
//                $sujeitoDespachoId,
//                $taxaIva,
//                $item->peso,
//                $input->horaExtra,
//                $horaAberturaAeroporto,
//                $horaFechoAeroporto,
//                $taxaAbertoAeroporto,
//                $cambioDia,
//                $taxaReaberturaComercial
//            );
//
//            $faturaAeronautico->addItem($faturaItemAeronautico);
//        }
//        return $faturaAeronautico;
    }
}
