<?php

namespace App\Infra\Factory\VendasOnline;


use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\CarrinhoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\ClienteRepository;
use App\Infra\Repository\VendasOnline\ComunasFreteRepository;
use App\Infra\Repository\VendasOnline\ExistenciaStockRepository;
use App\Infra\Repository\VendasOnline\FaturaRepository;
use App\Infra\Repository\VendasOnline\HistoricoPagamentoRepository;
use App\Infra\Repository\VendasOnline\MunicipiosFreteRepository;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\PerguntaFrequenteRepository;
use App\Infra\Repository\VendasOnline\RelatorioVendaOnlineJasper;
use App\Infra\Repository\VendasOnline\SubscricaoVendaOnlineRepository;
use App\Infra\Repository\VendasOnline\TipoEntregaRepository;
use App\Infra\Repository\VendasOnline\UserRepository;

class DatabaseRepositoryFactory implements RepositoryFactory
{


    public function createPagamentoVendaOnlineRepository(): PagamentoVendasOnlineRepository
    {
        return new PagamentoVendasOnlineRepository();
    }

    public function createCarrinhoVendaOnlineRepository(): CarrinhoVendasOnlineRepository
    {
        return new CarrinhoVendasOnlineRepository();
    }

    public function createRelatorioVendaOnlineJasper(): RelatorioVendaOnlineJasper
    {
        return new RelatorioVendaOnlineJasper();
    }

    public function createUserRepository(): UserRepository
    {
        return new UserRepository();
    }

    public function createTipoEntregaRepository(): TipoEntregaRepository
    {
        return new TipoEntregaRepository();
    }

    public function createMuniciposFreteRepository(): MunicipiosFreteRepository
    {
        return new MunicipiosFreteRepository();
    }

    public function createComunasFreteRepository(): ComunasFreteRepository
    {
        return new ComunasFreteRepository();
    }

    public function createClienteRepository(): ClienteRepository
    {
        return new ClienteRepository();
    }

    public function createFaturaRepository(): FaturaRepository
    {
        return new FaturaRepository();
    }

    public function createExistenciaStockRepository(): ExistenciaStockRepository
    {
        return new ExistenciaStockRepository();
    }

    public function createHistoricoPagamentoRepository(): HistoricoPagamentoRepository
    {
        return new HistoricoPagamentoRepository();
    }

    public function createSubscricaoVendaOnlineRepository(): SubscricaoVendaOnlineRepository
    {
        return new SubscricaoVendaOnlineRepository();
    }

    public function createPerguntaFrequenteRepository(): PerguntaFrequenteRepository
    {
        return new PerguntaFrequenteRepository();
    }
}
