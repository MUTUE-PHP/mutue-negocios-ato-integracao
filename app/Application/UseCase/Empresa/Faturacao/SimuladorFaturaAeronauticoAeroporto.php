<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemAeronautico;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Infra\Repository\Empresa\TaxaPesoMaximoDescolagemRepositoryRepository;
use Illuminate\Support\Facades\DB;

class SimuladorFaturaAeronauticoAeroporto
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


        $getTaxaEstacionameno = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $taxaEstacionamento = (float)$getTaxaEstacionameno->execute('tarifa_estacionamento')->valor;


        $getTaxaLuminosa = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $taxaLuminosa = (float)$getTaxaLuminosa->execute('tarifa_luminosa')->valor;

        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $moedaEstrageiraUsado = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;
        $cambioDia = DB::table('cambios')->where('designacao', $moedaEstrageiraUsado)->first()->valor;

        $getHoraAberturaAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $horaAberturaAeroporto = $getHoraAberturaAeroporto->execute('hora_abertura_aeroporto')->valor;

        $getHoraAberturaAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $horaFechoAeroporto = $getHoraAberturaAeroporto->execute('hora_fecho_aeroporto')->valor;

        $getTaxaAbertoAeroporto = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $taxaAbertoAeroporto = $getTaxaAbertoAeroporto->execute('tarifa_abertura_aeroporto')->valor;

        $considera1hDepois14min = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $considera1hDepois14min = $considera1hDepois14min->execute('considerar1hdepois14min')->valor;

        $getTaxaReaberturaComercial = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $taxaReaberturaComercial = (float)$getTaxaReaberturaComercial->execute('tarifa_reabertura_comercial')->valor;

        $horaAberturaAeroporto = $this->conversorHora($horaAberturaAeroporto);
        $horaFechoAeroporto = $this->conversorHora($horaFechoAeroporto);

        $faturaAeronautico = new FaturaAeronautico(
            $input->tipoDocumento,
            $input->formaPagamentoId,
            $input->observacao,
            $input->isencaoIVA,
            $input->retencao,
            $valorRetencao,
            $input->nomeProprietario,
            $input->clienteId,
            $input->nomeCliente,
            $input->telefoneCliente,
            $input->nifCliente,
            $input->emailCliente,
            $input->enderecoCliente,
            $input->tipoDeAeronave,
            $input->numeroAeronaveChegada ?? null,
            $input->numeroAeronavePartida ?? null,
            $input->horaMangaAcoplagem ?? null,
            $input->horaMangaDesacoplagem ?? null,
            $input->pesoMaximoDescolagem,
            $input->dataDeAterragem,
            $input->dataDeDescolagem,
            $input->horaDeAterragem,
            $input->horaDeDescolagem,
            $taxaIva,
            $input->peso,
            $input->horaExtra,
            $cambioDia,
            $moedaEstrageiraUsado,
            $input->moedaPagamento,
            $considera1hDepois14min
        );
        $peso = 0;
        foreach ($input->items as $item) {
            $item = (object)$item;
            $produto = DB::table('produtos')->where('id', $item->produtoId)->first();
            if ($item->produtoId == 12 || $item->produtoId == 13) {
                $peso += $item->peso;
                $faturaAeronautico->setPeso($item->peso);
                $faturaAeronautico->setPesoTotal($peso);
            }
            if ($item->produtoId == 7) {
                $faturaAeronautico->setPesoTotal($item->peso);
            }
            if ($item->produtoId == 7 || $item->produtoId == 12 || $item->produtoId == 13) {
                $taxaAduaneiraData = $this->taxaCargaAduaneiraRepository->getTaxaCargaById($item->sujeitoDespachoId);
                $taxaAduaneira = $taxaAduaneiraData['taxa'];
                $sujeitoDespachoId = $taxaAduaneiraData['id'];
            } else {
                $taxaAduaneira = 0;
                $sujeitoDespachoId = null;
            }
            $faturaItemAeronautico = new FaturaItemAeronautico(
                $item->produtoId,
                $item->nomeProduto,
                $produto->preco_venda,
                $item->desconto,
                $item->quantidade,
                $input->pesoMaximoDescolagem,
                $faturaAeronautico->getHoraEstacionamento(),
                $faturaAeronautico->getHoraMangaAcoplagemEDesacoplagem(),
                $taxaEstacionamento,
                $taxaLuminosa,
                $taxaAduaneira,
                $sujeitoDespachoId,
                $taxaIva,
                $item->peso,
                $input->horaExtra,
                $horaAberturaAeroporto,
                $horaFechoAeroporto,
                $taxaAbertoAeroporto,
                $cambioDia,
                $taxaReaberturaComercial
            );


            $faturaAeronautico->addItem($faturaItemAeronautico);

        }
        return $faturaAeronautico;
    }

}


class Taxa
{
    private $designacao;
    private $taxa;
    private $desconto;

    public function __construct($designacao, $taxa, $desconto)
    {
        $this->designacao = $designacao;
        $this->taxa = $taxa;
        $this->desconto = $desconto;
    }

    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * @return mixed
     */
    public function getTaxa()
    {
        return $this->taxa;
    }

    public function getDesconto()
    {
        return $this->desconto;
    }


}
