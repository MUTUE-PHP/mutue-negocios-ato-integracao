<?php

namespace App\Infra\Factory\Admin;
use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\AnuncioRepository;
use App\Infra\Repository\Admin\AtivacaoLicencaRepository;
use App\Infra\Repository\Admin\BancoRepository;
use App\Infra\Repository\Admin\ClienteRepository;
use App\Infra\Repository\Admin\EmpresaRepository;
use App\Infra\Repository\Admin\FaturaRepository;
use App\Infra\Repository\Admin\FormaPagamentoRepository;
use App\Infra\Repository\Admin\LicencaRepository;
use App\Infra\Repository\Admin\LogsUpdatePasswordClientRepository;
use App\Infra\Repository\Admin\PagamentoLicencaRepository;
use App\Infra\Repository\Admin\PermissaoRepository;
use App\Infra\Repository\Admin\RoleRepository;
use App\Infra\Repository\Admin\UserRepository;

class DatabaseRepositoryFactory implements RepositoryFactory{

    public function createPagamentoLicencaRepository(): PagamentoLicencaRepository
    {
       return new PagamentoLicencaRepository();
    }

    public function createFaturaRepository(): FaturaRepository
    {
        return new FaturaRepository();
    }

    public function createClienteRepository(): ClienteRepository
    {
        return new ClienteRepository();
    }

    public function createLicencaRepository(): LicencaRepository
    {
        return new LicencaRepository();
    }

    public function createFormaPagamentoRepository(): FormaPagamentoRepository
    {
        return new FormaPagamentoRepository();
    }

    public function createUserRepository(): UserRepository
    {
        return new UserRepository();
    }

    public function createBancoRepository(): BancoRepository
    {
        return new BancoRepository();
    }

    public function createAtivacaoLicencaRepository(): AtivacaoLicencaRepository
    {
       return new AtivacaoLicencaRepository();
    }

    public function createUpdatePasswordClientRepository(): LogsUpdatePasswordClientRepository
    {
        return new LogsUpdatePasswordClientRepository();
    }
    public function createAnuncioRepository(): AnuncioRepository{
        return new AnuncioRepository();
    }

    public function createRoleRepository(): RoleRepository
    {
        return new RoleRepository();
    }

    public function createPermissaoRepository(): PermissaoRepository
    {
        return new PermissaoRepository();
    }

    public function createEmpresaRepository(): EmpresaRepository
    {
        return new EmpresaRepository();
    }
}
