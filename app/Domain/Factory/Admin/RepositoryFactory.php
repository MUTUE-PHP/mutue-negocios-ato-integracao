<?php

namespace App\Domain\Factory\Admin;

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

interface RepositoryFactory
{
    public function createPagamentoLicencaRepository():PagamentoLicencaRepository;
    public function createFaturaRepository():FaturaRepository;
    public function createClienteRepository():ClienteRepository;
    public function createLicencaRepository():LicencaRepository;
    public function createFormaPagamentoRepository():FormaPagamentoRepository;
    public function createUserRepository():UserRepository;
    public function createBancoRepository():BancoRepository;
    public function createAtivacaoLicencaRepository():AtivacaoLicencaRepository;
    public function createUpdatePasswordClientRepository():LogsUpdatePasswordClientRepository;
    public function createAnuncioRepository():AnuncioRepository;
    public function createRoleRepository():RoleRepository;
    public function createPermissaoRepository():PermissaoRepository;
    public function createEmpresaRepository():EmpresaRepository;
}
