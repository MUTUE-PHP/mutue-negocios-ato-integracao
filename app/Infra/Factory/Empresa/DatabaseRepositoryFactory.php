<?php

namespace App\Infra\Factory\Empresa;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\CarrinhoRepository;
use App\Infra\Repository\CouponDescontoRepository;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\AtualizacaoStockRepository;
use App\Infra\Repository\Empresa\AtualizarEstoqueRepository;
use App\Infra\Repository\Empresa\BancoRepository;
use App\Infra\Repository\Empresa\CarateristicaProdutoRepository;
use App\Infra\Repository\Empresa\CartaoClienteRepository;
use App\Infra\Repository\Empresa\CategoriaRepository;
use App\Infra\Repository\Empresa\CentroCustoRepository;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\EmpresaRepository;
use App\Infra\Repository\Empresa\EntradaProdutoRepository;
use App\Infra\Repository\Empresa\EspecificacaoMercadoriaRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\ExtratoCartaoClienteRepository;
use App\Infra\Repository\Empresa\FabricanteRepository;
use App\Infra\Repository\Empresa\FaturaProformaRepository;
use App\Infra\Repository\Empresa\FaturaReciboRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\FormasPagamentoRepository;
use App\Infra\Repository\Empresa\FornecedorRepository;
use App\Infra\Repository\Empresa\InventarioRepository;
use App\Infra\Repository\Empresa\MotivosIsencaoRepository;
use App\Infra\Repository\Empresa\NotaCreditoFaturaRepository;
use App\Infra\Repository\Empresa\NotaCreditoReciboRepository;
use App\Infra\Repository\Empresa\NotaCreditoRepository;
use App\Infra\Repository\Empresa\NotaEntregaRepository;
use App\Infra\Repository\Empresa\OrderByProdutoRepository;
use App\Infra\Repository\Empresa\PaisRepository;
use App\Infra\Repository\Empresa\ParametroRepository;
use App\Infra\Repository\Empresa\ProdutoRepository;
use App\Infra\Repository\Empresa\ProvinciaRepository;
use App\Infra\Repository\Empresa\ReciboRepository;
use App\Infra\Repository\Empresa\SequenciaDocumentoRepository;
use App\Infra\Repository\Empresa\SequenciaFaturaRepository;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Infra\Repository\Empresa\TaxaIvaRepository;
use App\Infra\Repository\Empresa\TaxaPesoMaximoDescolagemRepositoryRepository;
use App\Infra\Repository\Empresa\TipoDocumentoRepository;
use App\Infra\Repository\Empresa\TipoMercadoriaRepository;
use App\Infra\Repository\Empresa\TipoServicoRepository;
use App\Infra\Repository\Empresa\UnidadesMedidaRepository;
use App\Infra\Repository\Empresa\UserRepository as UserRepositoryEmpresa;
use App\Infra\Repository\FaturaVendaOnlineRepository;
use App\Infra\Repository\PagamentoVendaOnlineRepository;
use App\Infra\Repository\UserRepository;

class DatabaseRepositoryFactory implements RepositoryFactory
{
    public function createPagamentoVendaOnlineRepository(): PagamentoVendaOnlineRepository
    {
        return new PagamentoVendaOnlineRepository();
    }
    public function createUserRepository(): UserRepository
    {
        return new UserRepository();
    }

    public function createFaturaPagamentoVendaOnlineRepository(): FaturaVendaOnlineRepository
    {
       return new FaturaVendaOnlineRepository();
    }

    public function createCouponDescontoRepository():CouponDescontoRepository
    {
        return new CouponDescontoRepository();
    }
    public function createCarrinhoRepository():CarrinhoRepository{
        return new CarrinhoRepository();
    }


    public function createSequenciaDocumentoRepository(): SequenciaDocumentoRepository
    {
        return new SequenciaDocumentoRepository();
    }


    public function createFaturaRepository(): FaturaRepository
    {
        return new FaturaRepository();
    }

    public function createReciboRepository(): ReciboRepository
    {
        return new ReciboRepository();
    }


    public function createNotaCreditoFaturaRepository(): NotaCreditoFaturaRepository
    {
        return new NotaCreditoFaturaRepository();
    }

    public function createNotaCreditoReciboRepository(): NotaCreditoReciboRepository
    {
        return new NotaCreditoReciboRepository();
    }

    public function createFaturaReciboRepository(): FaturaReciboRepository
    {
        return new FaturaReciboRepository();
    }

    public function createFaturaProformaRepository(): FaturaProformaRepository
    {
        return new FaturaProformaRepository();
    }

    public function createCartaoClienteRepository(): CartaoClienteRepository
    {
        return new CartaoClienteRepository();
    }

    public function createClienteRepository(): ClienteRepository
    {
        return new ClienteRepository();
    }

    public function createProdutoRepository(): ProdutoRepository
    {
        return new ProdutoRepository();
    }

    public function createArmazemRepository(): ArmazemRepository
    {
        return new ArmazemRepository();
    }

    public function createFormasPagamentoRepository(): FormasPagamentoRepository
    {
        return new FormasPagamentoRepository();
    }

    public function createExistenciaStockRepository(): ExistenciaStockRepository
    {
        return new ExistenciaStockRepository();
    }

    public function createEntradaProdutoRepository(): EntradaProdutoRepository
    {
        return new EntradaProdutoRepository();
    }

    public function createFornecedorRepository(): FornecedorRepository
    {
        return new FornecedorRepository();
    }

    public function createAtualizacaoStockRepository(): AtualizacaoStockRepository
    {
        return new AtualizacaoStockRepository();
    }

    public function createCategoriaRepository(): CategoriaRepository
    {
        return new CategoriaRepository();
    }

    public function createFabricanteRepository(): FabricanteRepository
    {
        return new FabricanteRepository();
    }

    public function createTaxaIvaRepository(): TaxaIvaRepository
    {
        return new TaxaIvaRepository();
    }

    public function createMotivoIsencaoRepository(): MotivosIsencaoRepository
    {
        return new MotivosIsencaoRepository();
    }

    public function createUnidadesMedidaRepository(): UnidadesMedidaRepository
    {
       return new UnidadesMedidaRepository();
    }

    public function createOrderByProdutoRepository(): OrderByProdutoRepository
    {
       return new OrderByProdutoRepository();
    }

    public function createCarateristicaRepository(): CarateristicaProdutoRepository
    {
        return new CarateristicaProdutoRepository();
    }

    public function createProvinciaRepository(): ProvinciaRepository
    {
        return new ProvinciaRepository();
    }

    public function createCentroCustoRepository(): CentroCustoRepository
    {
        return new CentroCustoRepository();
    }


    public function createSequenciaFaturaRepository(): SequenciaFaturaRepository
    {
        return new SequenciaFaturaRepository();
    }

    public function createAtualizarEstoqueRepository(): AtualizarEstoqueRepository
    {
        return new AtualizarEstoqueRepository();
    }

    public function createParametroRepository():ParametroRepository
    {
        return new ParametroRepository();
    }

    public function createExtratoCartaoClienteRepository(): ExtratoCartaoClienteRepository
    {
        return new ExtratoCartaoClienteRepository();
    }
    public function createUserRepositoryEmpresa(): UserRepositoryEmpresa
    {
        return new UserRepositoryEmpresa();
    }
    public function createInventarioRepository(): InventarioRepository
    {
        return new InventarioRepository();
    }

    public function createNotaEntregaRepository(): NotaEntregaRepository
    {
        return new NotaEntregaRepository();
    }

    public function createEmpresaRepository(): EmpresaRepository
    {
        return new EmpresaRepository();
    }

    public function createPaisRepository(): PaisRepository
    {
        return new PaisRepository();
    }

    public function createTipoDocumentoRepository(): TipoDocumentoRepository
    {
        return new TipoDocumentoRepository();
    }

    public function createTipoMercadoriaRepository(): TipoMercadoriaRepository
    {
        return new TipoMercadoriaRepository();
    }

    public function createTipoServicoRepository(): TipoServicoRepository
    {
        return new TipoServicoRepository();
    }

    public function createTaxaCargaAduaneiraRepository(): TaxaCargaAduaneiraRepository
    {
        return new TaxaCargaAduaneiraRepository();
    }

    public function createEspecificacaoMercadoriaRepository(): EspecificacaoMercadoriaRepository
    {
        return new EspecificacaoMercadoriaRepository();
    }

    public function createBancoRepository(): BancoRepository
    {
        return new BancoRepository();
    }

    public function createTaxaPesoMaximoDescolagemRepositoryRepository(): TaxaPesoMaximoDescolagemRepositoryRepository
    {
        return new TaxaPesoMaximoDescolagemRepositoryRepository();
    }

    public function createNotaCreditoRepository(): NotaCreditoRepository
    {
        return new NotaCreditoRepository();
    }
}
