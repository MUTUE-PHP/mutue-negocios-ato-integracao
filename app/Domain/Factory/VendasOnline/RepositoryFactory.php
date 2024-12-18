<?php

namespace App\Domain\Factory\VendasOnline;
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

interface RepositoryFactory
{
    public function createPagamentoVendaOnlineRepository():PagamentoVendasOnlineRepository;
    public function createCarrinhoVendaOnlineRepository():CarrinhoVendasOnlineRepository;
    public function createRelatorioVendaOnlineJasper():RelatorioVendaOnlineJasper;
    public function createUserRepository():UserRepository;
    public function createTipoEntregaRepository():TipoEntregaRepository;
    public function createMuniciposFreteRepository():MunicipiosFreteRepository;
    public function createComunasFreteRepository():ComunasFreteRepository;
    public function createClienteRepository():ClienteRepository;
    public function createFaturaRepository():FaturaRepository;
    public function createExistenciaStockRepository():ExistenciaStockRepository;
    public function createHistoricoPagamentoRepository():HistoricoPagamentoRepository;
    public function createSubscricaoVendaOnlineRepository():SubscricaoVendaOnlineRepository;
    public function createPerguntaFrequenteRepository():PerguntaFrequenteRepository;
}
