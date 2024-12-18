<?php

use App\Http\Controllers\admin\AdminSemPermissaoController;
use App\Http\Controllers\admin\AtivacaoLicencaController;
use App\Http\Controllers\admin\BackupBancoIndexController as AdminBackupBancoIndexController;
use App\Http\Controllers\admin\BancoController;
use App\Http\Controllers\admin\Bancos\BancoCreateController as AdminBancoCreateController;
use App\Http\Controllers\admin\Bancos\BancoIndexController as AdminBancoIndexController;
use App\Http\Controllers\admin\Bancos\BancoUpdateController as AdminBancoUpdateController;
use App\Http\Controllers\admin\Clientes\ClienteDetalhesController as AdminClienteDetalhesController;
use App\Http\Controllers\admin\Clientes\ClienteIndexController as AdminClienteIndexController;
use App\Http\Controllers\admin\Configuracao\MinhaContaIndexController as AdminMinhaContaIndexController;
use App\Http\Controllers\admin\ConfiguracaoController;
use App\Http\Controllers\admin\CoordernadaBancariaController;
use App\Http\Controllers\admin\EmpresaController as AdminEmpresaController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\HomeController as AdminHomeController;
use App\Http\Controllers\admin\LicencaController;
use App\Http\Controllers\admin\LicencaPagamentos\PagamentoLicencaCreateController;
use App\Http\Controllers\admin\LicencaPagamentos\PagamentoLicencaIndexController;
use App\Http\Controllers\admin\Licencas\LicencaIndexController as AdminLicencaIndexController;
use App\Http\Controllers\admin\MotivoIsencao\MotivoIsencaoCreateController as AdminMotivoIsencaoCreateController;
use App\Http\Controllers\admin\MotivoIsencao\MotivoIsencaoIndexController as AdminMotivoIsencaoIndexController;
use App\Http\Controllers\admin\MotivoIsencao\MotivoIsencaoUpdateController as AdminMotivoIsencaoUpdateController;
use App\Http\Controllers\admin\MotivoIvaController as AdminMotivoIvaController;
use App\Http\Controllers\admin\NotificacaoAvisos\NotificacaoAvisoCreateController;
use App\Http\Controllers\admin\NotificacaoAvisos\NotificacaoAvisoIndexController;
use App\Http\Controllers\admin\NotificacaoController;
use App\Http\Controllers\admin\PedidosLicenca\PedidoLicencaAtivarIndexController as AdminPedidoLicencaAtivarIndexController;
use App\Http\Controllers\admin\PedidosLicenca\PedidoLicencaDetalhesShowController as AdminPedidoLicencaDetalhesShowController;
use App\Http\Controllers\admin\PedidosLicenca\PedidoLicencaRejeitadoIndexController as AdminPedidoLicencaRejeitadoIndexController;
use App\Http\Controllers\admin\PedidosLicenca\PedidosLicencaIndexController as AdminPedidosLicencaIndexController;
use App\Http\Controllers\admin\PedidoUtilizador\AdminPedidosAtivacaoUtilizadorIndexController;
use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\ResetarSenhaEmpresa\ResetarSenhaEmpresaCreateController;
use App\Http\Controllers\admin\ResetarSenhaEmpresa\ResetarSenhaEmpresaIndexController;
use App\Http\Controllers\admin\TaxaIva\TaxaIvaCreateController as AdminTaxaIvaCreateController;
use App\Http\Controllers\admin\TaxaIva\TaxaIvaIndexController as AdminTaxaIvaIndexController;
use App\Http\Controllers\admin\TaxaIva\TaxaIvaUpdateController as AdminTaxaIvaUpdateController;
use App\Http\Controllers\admin\TaxaIvaController as AdminTaxaIvaController;
use App\Http\Controllers\admin\UserController as AdminUserController;
use App\Http\Controllers\admin\Users\AdminRolePermissoesIndexController;
use App\Http\Controllers\admin\Users\AdminUserPerfilController;
use App\Http\Controllers\admin\Users\AdminUserPerfilCreateController;
use App\Http\Controllers\admin\Users\AdminUserPerfilUpdateController;
use App\Http\Controllers\admin\Users\AdminUserPermissaoController;
use App\Http\Controllers\admin\Users\AdminUserPermissoesIndexController;
use App\Http\Controllers\admin\Users\UserCreateController as AdminUserCreateController;
use App\Http\Controllers\admin\Users\UserIndexController as AdminUserIndexController;
use App\Http\Controllers\admin\Users\UserUpdateController as AdminUserUpdateController;
use App\Http\Controllers\admin\Users\UserUpdatePasswordController as AdminUserUpdatePasswordController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthClienteController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaixarManualController;
use App\Http\Controllers\empresa\ArmazenController;
use App\Http\Controllers\empresa\Armazens\ArmazemCreateController;
use App\Http\Controllers\empresa\Armazens\ArmazemIndexController;
use App\Http\Controllers\empresa\Armazens\ArmazemUpdateController;
use App\Http\Controllers\empresa\Assinaturas\AssinaturaIndexController;
use App\Http\Controllers\empresa\AtualizacaoEstoque\AtualizacaoEstoqueCreateController;
use App\Http\Controllers\empresa\AtualizacaoEstoque\AtualizacaoEstoqueIndexController;
use App\Http\Controllers\empresa\Bancos\BancoCreateController;
use App\Http\Controllers\empresa\Bancos\BancoIndexController;
use App\Http\Controllers\empresa\Bancos\BancoUpdateController;
use App\Http\Controllers\empresa\Banner\AnuncioBannerCreateController;
use App\Http\Controllers\empresa\Banner\AnuncioBannerEditController;
use App\Http\Controllers\empresa\Banner\AnuncioBannerIndexController;
use App\Http\Controllers\empresa\CartaoCliente\BonusCartaoClienteIndexController;
use App\Http\Controllers\empresa\CartaoCliente\CartaoClienteCreateController;
use App\Http\Controllers\empresa\CartaoCliente\CartaoClienteIndexController;
use App\Http\Controllers\empresa\CategoriaController;
use App\Http\Controllers\empresa\Categorias\CategoriaCreateController;
use App\Http\Controllers\empresa\Categorias\CategoriaIndexControlle;
use App\Http\Controllers\empresa\Categorias\CategoriaIndexController;
use App\Http\Controllers\empresa\Categorias\CategoriaUpdateController;
use App\Http\Controllers\empresa\Categorias\CategoriaUpdateSubCategoriaController;
use App\Http\Controllers\empresa\CentroCustos\CentroCustoCreateController;
use App\Http\Controllers\empresa\CentroCustos\CentroCustoIndexController;
use App\Http\Controllers\empresa\CentroCustos\CentroCustoRelatorioIndexController;
use App\Http\Controllers\empresa\CentroCustos\CentroCustoUpdateController;
use App\Http\Controllers\empresa\CentroCustos\OpcaoCentroCustoIndexController;
use App\Http\Controllers\empresa\ClasseController;
use App\Http\Controllers\empresa\ClienteController;
use App\Http\Controllers\empresa\Clientes\ClienteCreateController;
use App\Http\Controllers\empresa\Clientes\ClienteExtratoController;
use App\Http\Controllers\empresa\Clientes\ClienteIndexController;
use App\Http\Controllers\empresa\Clientes\ClienteShowController;
use App\Http\Controllers\empresa\Clientes\ClienteUpdateController;
use App\Http\Controllers\empresa\Clientes\RelatorioExtratoClienteController;
use App\Http\Controllers\empresa\ComunasFrete\ComunaFreteCreateController;
use App\Http\Controllers\empresa\ComunasFrete\ComunaFreteIndexController;
use App\Http\Controllers\empresa\ComunasFrete\ComunaFreteUpdateController;
use App\Http\Controllers\empresa\ConfiguracaoController as EmpresaConfiguracaoController;
use App\Http\Controllers\empresa\CuponDesconto\CuponDescontoCreateController;
use App\Http\Controllers\empresa\CuponDesconto\CuponDescontoIndexController;
use App\Http\Controllers\empresa\Empresa\EmpresaIndexController;
use App\Http\Controllers\empresa\Empresa\EmpresaUpdateController;
use App\Http\Controllers\empresa\EmpresaController;
use App\Http\Controllers\empresa\EntradaProduto\EntradaProdutoCreateController;
use App\Http\Controllers\empresa\EntradaProduto\EntradaProdutoIndexController;
use App\Http\Controllers\empresa\ExistenciaStockController;
use App\Http\Controllers\empresa\Fabricantes\FabricanteCreateController;
use App\Http\Controllers\empresa\Fabricantes\FabricanteIndexController;
use App\Http\Controllers\empresa\Fabricantes\FabricanteUpdateController;
use App\Http\Controllers\empresa\FacturacaoController;
use App\Http\Controllers\empresa\FacturaController;
use App\Http\Controllers\empresa\Facturas\FacturaProformaIndexController;
use App\Http\Controllers\empresa\Facturas\FacturasAeroportuarioIndexController;
use App\Http\Controllers\empresa\Facturas\FacturasIndexController;
use App\Http\Controllers\empresa\Facturas\FacturasOutroServicoIndexController;
use App\Http\Controllers\empresa\Facturas\FacturasServicoComercialIndexController;
use App\Http\Controllers\empresa\Faturacao\EmissaoFaturaAeronauticoController;
use App\Http\Controllers\empresa\Faturacao\EmissaoFaturaCargaController;
use App\Http\Controllers\empresa\Faturacao\EmissaoFaturaController;
use App\Http\Controllers\empresa\Faturacao\EmissaoFaturaOutroServicoController;
use App\Http\Controllers\empresa\Faturacao\EmissaoFaturaServicoComercialController;
use App\Http\Controllers\empresa\Faturacao\EmitirDocumentoController;
use App\Http\Controllers\empresa\Faturacao\FaturacaoCreateController;
use App\Http\Controllers\empresa\Faturacao\FaturacaoIndexController;
use App\Http\Controllers\empresa\FechoCaixa\FechoCaixaIndexController;
use App\Http\Controllers\empresa\FechoCaixa\RelatorioGeralIndexController;
use App\Http\Controllers\empresa\FechoCaixaController;
use App\Http\Controllers\empresa\FinalizarVendaController;
use App\Http\Controllers\empresa\FormaPagamentoController;
use App\Http\Controllers\empresa\FormasPagamento\FormaPagamentoIndexController;
use App\Http\Controllers\empresa\FornecedorController;
use App\Http\Controllers\empresa\Fornecedores\FornecedorCreateController;
use App\Http\Controllers\empresa\Fornecedores\FornecedorIndexController;
use App\Http\Controllers\empresa\Fornecedores\FornecedorShowController;
use App\Http\Controllers\empresa\Fornecedores\FornecedorUpdateController;
use App\Http\Controllers\empresa\GestorController;
use App\Http\Controllers\empresa\InventarioController;
use App\Http\Controllers\empresa\Inventarios\InventarioCreateController;
use App\Http\Controllers\empresa\Inventarios\InventarioIndexController;
use App\Http\Controllers\empresa\LicencaController as EmpresaLicencaController;
use App\Http\Controllers\empresa\Licencas\MinhaLicencaController;
use App\Http\Controllers\empresa\LogAcesso\LogAcessoIndexController;
use App\Http\Controllers\empresa\ManualUtilizador\ManualUtilizadorIndexController;
use App\Http\Controllers\empresa\MarcaController;
use App\Http\Controllers\empresa\Marcas\MarcaCreateController;
use App\Http\Controllers\empresa\Marcas\MarcaIndexController;
use App\Http\Controllers\empresa\Marcas\MarcaUpdateController;
use App\Http\Controllers\empresa\mercadorias\MercadoriaIndexController;
use App\Http\Controllers\empresa\mercadorias\EspecificacaoMercadoriaController;
use App\Http\Controllers\empresa\Cambio\CambioController;
use App\Http\Controllers\empresa\FechoCaixa\RelatorioGeraisTodosController;
use App\Http\Controllers\empresa\ModeloDocumentos\ModeloDocumentoController;
use App\Http\Controllers\empresa\ModuloAcesso\ModuloAcessoIndexController;
use App\Http\Controllers\empresa\MotivoIvaController;
use App\Http\Controllers\empresa\MunicipiosFrete\MunicipioFreteCreateController;
use App\Http\Controllers\empresa\MunicipiosFrete\MunicipioFreteIndexController;
use App\Http\Controllers\empresa\MunicipiosFrete\MunicipioFreteUpdateController;
use App\Http\Controllers\empresa\NotaCreditoAnulacaoDocumentos\AnulacaoDocumentoFacturaCreateController;
use App\Http\Controllers\empresa\NotaCreditoAnulacaoDocumentos\AnulacaoDocumentoIndexController;
use App\Http\Controllers\empresa\NotaCreditoAnulacaoDocumentos\AnulacaoDocumentoReciboCreateController;
use App\Http\Controllers\empresa\NotaCreditoAnulacaoDocumentos\RetificacaoDocumentoCreateController;
use App\Http\Controllers\empresa\NotaCreditoAnulacaoDocumentos\RetificacaoDocumentoIndexController;
use App\Http\Controllers\empresa\NotaCreditoSaldoCliente\NotaCreditoCreateController;
use App\Http\Controllers\empresa\NotaCreditoSaldoCliente\NotaCreditoIndexController;
use App\Http\Controllers\empresa\NotaDebitoSaldoCliente\NotaDebitoCreateController;
use App\Http\Controllers\empresa\NotaDebitoSaldoCliente\NotaDebitoIndexController;
use App\Http\Controllers\empresa\NotasEntrega\NotaEntregaIndexController;
use App\Http\Controllers\empresa\NotificacaoController as EmpresaNotificacaoController;
use App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoFaturaComercialCreateController;
use App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoFaturaCreateController;
use App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoFaturaIndexController;
use App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoFaturaOutroServicoCreateController;
use App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoReciboIndexController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaAeroportuarioCreateController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaCargaCreateController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaCreateController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaIndexController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaOutroServicoCreateController;
use App\Http\Controllers\empresa\Operacao\RetificacaoDocumentoFaturaServicoComercialCreateController;
use App\Http\Controllers\empresa\Operacao\TransferenciaStockController;
use App\Http\Controllers\empresa\OperacaoController;
use App\Http\Controllers\empresa\PagamentoController;
use App\Http\Controllers\empresa\Parametros\ParametroIndexController;
use App\Http\Controllers\empresa\Pdv\PdvCreateController;
use App\Http\Controllers\empresa\PdvController;
use App\Http\Controllers\empresa\Permissao\PermissaoIndexController;
use App\Http\Controllers\empresa\Permissao\SemPermissaoController;
use App\Http\Controllers\empresa\PermissaoCentroCusto\PermissaoCentroCustoIndexController;
use App\Http\Controllers\empresa\PermissionController as EmpresaPermissionController;
use App\Http\Controllers\empresa\ProdutoController;
use App\Http\Controllers\empresa\ProdutoDestaques\ProdutoDestaqueCreateController;
use App\Http\Controllers\empresa\ProdutoDestaques\ProdutoDestaqueIndexController;
use App\Http\Controllers\empresa\ProdutoDestaques\ProdutoDestaqueUpdateController;
use App\Http\Controllers\empresa\Produtos\ProdutoCreateController;
use App\Http\Controllers\empresa\Produtos\ProdutoDescricaoController;
use App\Http\Controllers\empresa\Produtos\ProdutoIndexController;
use App\Http\Controllers\empresa\Produtos\ProdutoMaisVendidosIndexController;
use App\Http\Controllers\empresa\Produtos\ProdutoShowController;
use App\Http\Controllers\empresa\Produtos\ProdutoStoreController;
use App\Http\Controllers\empresa\Produtos\ProdutoUpdateController;
use App\Http\Controllers\empresa\Proformas\ProformaIndexController;
use App\Http\Controllers\empresa\Recibos\ReciboCreateController;
use App\Http\Controllers\empresa\Recibos\ReciboIndexController;
use App\Http\Controllers\empresa\RelatorioController;
use App\Http\Controllers\empresa\ReportController;
use App\Http\Controllers\empresa\RoleController as EmpresaRoleController;
use App\Http\Controllers\empresa\Roles\RoleController;
use App\Http\Controllers\empresa\Roles\RoleCreateController;
use App\Http\Controllers\empresa\Roles\RoleIndexController;
use App\Http\Controllers\empresa\Roles\RolePermissoesIndexController;
use App\Http\Controllers\empresa\Roles\RoleUpdateController;
use App\Http\Controllers\empresa\RolesPermissionController;
use App\Http\Controllers\empresa\Safts\GerarSaftController;
use App\Http\Controllers\empresa\SequenciaDocumentos\SequenciaDocumentoIndexController;
use App\Http\Controllers\empresa\StockController;
use App\Http\Controllers\empresa\TaxaIvaController;
use App\Http\Controllers\empresa\TipoDocumentoController;
use App\Http\Controllers\empresa\UnidadeController;
use App\Http\Controllers\empresa\UnidadeMedidas\UnidadeMedidaCreateController;
use App\Http\Controllers\empresa\UnidadeMedidas\UnidadeMedidaIndexController;
use App\Http\Controllers\empresa\UnidadeMedidas\UnidadeMedidaUpdateController;
use App\Http\Controllers\empresa\UserController;
use App\Http\Controllers\empresa\Usuarios\UsuarioCreateController;
use App\Http\Controllers\empresa\Usuarios\UsuarioIndexController;
use App\Http\Controllers\empresa\Usuarios\UsuarioPermissoesIndexontroller;
use App\Http\Controllers\empresa\Usuarios\UsuarioUpdateController;
use App\Http\Controllers\empresa\VendaController;
use App\Http\Controllers\empresa\Vendas\VendasDiariaIndexController;
use App\Http\Controllers\empresa\Vendas\VendasMensalIndexController;
use App\Http\Controllers\FrontOffice\FrontOfficeController;
use App\Http\Controllers\GenericoController;
use App\Http\Controllers\HomeController as EmpresaHomeController;
use App\Http\Controllers\VM\Pagamentos\PagamentoVendaOnlineIndexController;
use App\Http\Controllers\VM\PerguntasFrequente\PerguntaFrequenteIndexController;
use App\Jobs\JobCreateNewUser;
use App\Mail\MailCreateNewUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Fabricantes

// Bancos


// use Admins


// Bancos

// Clientes
// Licenças

//Empresa

// TaxaIva

// Motivo Isenção
// use App\Http\Controllers\admin\Bancos\BancoCreateController as AdminBancoCreateController;
// use App\Http\Controllers\admin\Bancos\BancoUpdateController as AdminBancoUpdateController;

// PedidoLicencaRejeitadoIndexController


/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Auth::routes();


Route::get('/select2', [\App\Http\Controllers\Select2::class]);


Route::get('/proforma', function () {
    $faturaProformas = \App\Models\empresa\Factura::with(['facturas_items'])
        ->where('convertido', 'Y')
        ->whereHas('facturas_items', function ($query) {
            $query->where('dataEntrada', '!=', null)
                ->where('dataSaida', '!=', null);
        })->get();
    dd($faturaProformas);
});


Route::get('/hash', function () {
    /**
     * FT
     * FR
     * FP
     */
    $currectHash = new \App\Domain\Entity\Empresa\CorrectHash\CurrectHash('FT', 'facturas', false);
    dd($currectHash->execute());
//    // Conteúdo que você deseja imprimir
//    $conteudo = "Olá, este é o conteúdo que desejo imprimir.";
//
//// Nome da impressora
//    $nome_impressora = "Nome da Sua Impressora";
//
//// Comando para imprimir (pode variar dependendo do sistema operacional)
//    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
//        // Para Windows
//        exec("print /D:" . $nome_impressora . " \"" . $conteudo . "\"");
//    } else {
//        // Para sistemas baseados em Unix (Linux, macOS)
//        exec("lp -d " . $nome_impressora . " <<< \"" . $conteudo  .  "\"");
//}
});
Route::get('/uuid', function () {
    dd(Hash::make('mutue123'));
    $motivos = DB::connection('mysql')->table('motivo')->get();
    $string = "";
    foreach ($motivos as $motivo) {

        $string .= "[
       'codigo' => '$motivo->codigo',
       'codigoMotivo'=> '$motivo->codigoMotivo',
       'descricao'=> '$motivo->descricao',
       'codigoStatus'=> $motivo->codigoStatus,
       'user_id'=> $motivo->user_id,
       'canal_id'=> $motivo->canal_id,
       'status_id'=> $motivo->status_id,
       'empresa_id'=> null,
       'created_at'=> '$motivo->created_at',
       'updated_at'=> '$motivo->updated_at',
       ],";
    }

    dd($string);

    //$userRepository = new \App\Infra\Repository\UserRepository();
    //$user = $userRepository->getUser('211f5b80-f0f9-4efe-ab7e-db6e06a49eba');
    //dd($user);

//     return view("alert.msg_criacao_empresa_mobile");

//     $clientes = DB::table('clientes')->get();

//     foreach ($clientes as $key => $cliente) {

//         DB::table('clientes')->where('id', $cliente->id)->update([
//             'uuid' => Str::uuid()
//         ]);
//     }

});

Route::get('/user_perfil', function () {


    $users = DB::table('users_cliente')->get();

    foreach ($users as $key => $user) {

        DB::table('user_perfil')->insert([
            'user_id' => $user->id,
            'perfil_id' => 1
        ]);
    }
});


// Route::get('/csrf-cookie', function (Request $request) {
//     return response()->json(['csrf_token' => csrf_token()]);
// });

//Rota para nova tela de faturação
Route::get('/empresa/pdv', PdvCreateController::class)->middleware('Anuncio');


// ROTAS NOVAS V1
//Empresa
Route::get('/', [AppController::class, 'home'])->middleware('Anuncio');

Route::get('/politica-privacidade', function () {
    return view("politicaPrivacidade");
});
Route::get('/home', [AppController::class, 'home'])->name('home');
Route::get('/cadastro-empresa', [AdminEmpresaController::class, 'create']);

Route::get('/criar-empresa', EmpresaIndexController::class);


Route::post('baixar-manual', [BaixarManualController::class, 'imprimirManualUtilizador'])->name('baixar.manual');


// FIM ROTAS NOVAS V1
Route::get('/login', [AppController::class, 'home']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'empresa'], function () {
    Route::get('cliente-login', [AuthClienteController::class, 'showLoginForm'])->name('cliente-login');
    Route::post('login-cliente', [AuthClienteController::class, 'login'])->name('login-cliente');
});
Route::post('/validar-empresa', [RegisterController::class, 'validarEmpresa']);
Route::get('/register', [RegisterController::class, 'register']);

Route::group(['middleware' => 'web'], function () {
    Route::get('empresa-login', [LoginController::class, 'loginForm'])->name('empresa-login'); //Login ou autenticação das empresas;
    Route::get('/contacto', [FrontOfficeController::class, 'contactos']);
    Route::post('/contacto', [FrontOfficeController::class, 'enviarMensagem']);
    Route::get('/sobre', [FrontOfficeController::class, 'sobre']);
    Route::get('/servicos', [FrontOfficeController::class, 'servicos']);
});

/**
 * ROTAS GENERICAS
 *
 */
Route::get('/paises', [GenericoController::class, 'paises']);
Route::get('/tipoCliente', [GenericoController::class, 'tipoCliente']);
// Route::get('/tipoCliente', 'GenericoController@tipoCliente');

/**
 * ROTAS DA PARTE ADMINISTRATIVA
 */
//Rota página inicial

Route::group(['middleware' => ['auth:empresa']], function () {
    //Route::get('/empresa/home', [EmpresaHomeController::class, 'index'])->name('home');
    Route::get('/empresa/infoDashboard', [EmpresaHomeController::class, 'infoDashboard']);
    Route::get('/perfil', [EmpresaHomeController::class, 'perfil'])->name('perfil');
    // Route::post('/update_senha/{id}', 'admin\UserController@update_senha');
    //Route::post('/alterar-password/{id}', 'admin\UserController@alterarPassword');

    Route::get('empresa/perfil', [HomeController::class, 'perfil'])->name('empresa/perfil');
    // Route::post('empresa/update_senha/{id}', 'empresa\UserController@update_senha');
});

//usuários admin
// Route::group(['middleware' => ['role_or_permission:Super-Admin|Admin', 'auth']], function () {

Route::get('Unauthorized', function () {
    return response()->json(['error' => 'Unauthorized'], 401);
})->name('Unauthorized');

Route::get('acessoLink', [\App\Http\Controllers\Api\Utilizadores\UserController::class, 'recuperacaoDeSenha']);

Route::group(['middleware' => ['auth:web']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/sempermissao', AdminSemPermissaoController::class)->name('semPermissaoAdmin.index');
        Route::get('/', [AdminHomeController::class, 'index'])->name('admin.home');
        Route::get('/home', [AdminHomeController::class, 'index'])->name("adminHome");
        // Utilizadores
        Route::get('/utilizadores', AdminUserIndexController::class)->name('admin.users.index');
        Route::get('/utilizador/novo', AdminUserCreateController::class)->name('admin.users.create')->middleware('hasPermission:gerir utilizadores');
        Route::get('/utilizador/editar/{utilizadorId}', AdminUserUpdateController::class)->name('admin.users.edit');
        Route::get('/utilizador/perfil', AdminUserUpdatePasswordController::class);
        Route::post('/utilizador/perfil', AdminUserUpdatePasswordController::class);

        Route::get('/utilizadores/perfil', AdminUserPerfilController::class)->name('admin.users.funcao');
        Route::get('/utilizadores/perfil/novo', AdminUserPerfilCreateController::class)->name('adminUserPerfilNovo');
        Route::get('/utilizadores/perfil/{uuid}', AdminUserPerfilUpdateController::class)->name('adminRoleUpdate');
        Route::get('/utilizadores/permissoes', AdminUserPermissaoController::class)->name('admin.users.permissao');
        Route::get('/utilizadores/perfil/{uuid}/permissoes', AdminRolePermissoesIndexController::class)->name('AdminRolesPermissao');
        Route::get('/utilizador/{id}/permissoes', AdminUserPermissoesIndexController::class)->name('AdminUsersPermissoes');


        // Bancos
        Route::get('/bancos', AdminBancoIndexController::class)->name('admin.bancos.index');
        Route::get('/banco/novo', AdminBancoCreateController::class)->name('admin.bancos.create')->middleware('hasPermission:gerir bancos');
        Route::get('/banco/editar/{uuid}', AdminBancoUpdateController::class)->name('admin.bancos.edit')->middleware('hasPermission:gerir bancos');

        // Clientes
        Route::get('/clientes', AdminClienteIndexController::class)->name('admin.clientes.index')->middleware('hasPermission:Gerir clientes');
        Route::get('/cliente/{id}/detalhes', AdminClienteDetalhesController::class)->name('admin.clientes.detalhes')->middleware('hasPermission:Gerir clientes');
        // Licenças
        Route::get('/licencas', AdminLicencaIndexController::class)->name('admin.licencas.index');

        // Taxa Iva
        Route::get('/taxaIva', AdminTaxaIvaIndexController::class)->name('taxaIva.index');
        Route::get('/taxaIva/novo', AdminTaxaIvaCreateController::class)->name('admin.taxas.create');
        Route::get('/taxaIva/editar/{id}', AdminTaxaIvaUpdateController::class)->name('admin.taxas.edit');

        // Motivo isencao
        Route::get('/motivoisencao', AdminMotivoIsencaoIndexController::class)->name('motivoIsencao.index');
        Route::get('/motivoisencao/novo', AdminMotivoIsencaoCreateController::class)->name('motivoIsencao.create');
        Route::get('/motivoisencao/edit/{id}', AdminMotivoIsencaoUpdateController::class)->name('motivoIsencao.edit');

        Route::get('/licenca/novo', AdminUserCreateController::class)->name('admin.licencas.create');
        Route::get('/licencas/editar/{uuid}', AdminUserUpdateController::class)->name('admin.licencas.edit');

        // Minha conta
        Route::get('/empresa/conta', AdminMinhaContaIndexController::class)->name('configContaEmpresa')->middleware('hasPermission:Atualizar dados da empresa');

        //Resetar senha
        Route::get('/resetar/senha', ResetarSenhaEmpresaIndexController::class)->name('resetarSenhaClienteIndex')->middleware('hasPermission:Resetar a senha dos clientes');
        Route::get('/resetar/senha/novo', ResetarSenhaEmpresaCreateController::class)->name('resetarSenhaClienteCreate')->middleware('hasPermission:Resetar a senha dos clientes');


        // Pedidos de licenças
        Route::get('/pedidos/licenca', AdminPedidosLicencaIndexController::class)->name('admin.pedidosLicencas.index')->middleware('hasPermission:Gerir pedidos ativacao de licenca');
        Route::get('/pedido/licenca/{pedidoId}/detalhes', AdminPedidoLicencaDetalhesShowController::class)->name('pedidoLicencaDetalhes');
        Route::get('/pedido/licenca/{pedidoId}/rejeitar', AdminPedidoLicencaRejeitadoIndexController::class)->name('pedidoLicencaRejeitadoAdmin');
        Route::get('/pedido/licenca/{pedidoId}/ativar', AdminPedidoLicencaAtivarIndexController::class)->name('pedidoLicencaAtivarAdmin');
        Route::POST('/pedido/licenca/{pedidoId}/ativar', AdminPedidoLicencaAtivarIndexController::class)->name('pedidoLicencaAtivarAdmin');

        //Pedidos ativação dos utilizadores
        Route::get('/pedidos/ativacao/utilizadores', AdminPedidosAtivacaoUtilizadorIndexController::class)->name('pedidosActivacaoUtilizador')->middleware('hasPermission:Gerir activação de utilizador dos clientes');


        //Backup
        Route::get('/backup/banco', AdminBackupBancoIndexController::class)->name('backupBancoIndex')->middleware('hasPermission:Efetuar backup do banco de dados');
        Route::post('/backup/banco/cliente', [AdminBackupBancoIndexController::class, 'exportarBancoCliente'])->name('exportarBancoDadoCliente');
        Route::post('/backup/banco/admin', [AdminBackupBancoIndexController::class, 'exportarBancoAdmin'])->name('exportarBancoDadoAdmin');

        //notificações
        Route::get('admin/notifications', [NotificacaoController::class, 'notifications']);
        Route::get('admin/notificationsAll', [NotificacaoController::class, 'notificationsAll']);
        Route::put('admin/notificationsRead', [NotificacaoController::class, 'markAsRead']);
        Route::get('admin/listar-notificacoes', [NotificacaoController::class, 'listarNotificacoes']);
        Route::get('admin/notifications/deletar/{id}', [NotificacaoController::class, 'deletar']);

        //Empresa
        Route::post('admin/empresa/editar', [ConfiguracaoController::class, 'update']);
        Route::get('/admin/configuracao', [ConfiguracaoController::class, 'editarEmpresa']);

        //numeracao Documento


        //Utilizador
        Route::get('admin/usuarios', [AdminUserController::class, 'index'])->name('AdminUserIndex');
        Route::post('admin/utilizador/adicionar', [AdminUserController::class, 'store']);
        Route::post('admin/utilizador/{id}/update', [AdminUserController::class, 'update']);
        Route::get('admin/usuarios/delete/{id}', [AdminUserController::class, 'destroy']);
        Route::get('admin/usuario/perfil', [AdminUserController::class, 'perfilUtilizador']);
        Route::post('admin/usuario/updateSenha/{userId}', [AdminUserController::class, 'updateSenha']);


        // Route::resource('/admin/licencas', 'admin\LicencaController');
        Route::get('/admin/licencas', [LicencaController::class, 'index']);
        Route::resource('/admin/bancos', BancoController::class);
        Route::get('/admin/imprimirBancos', [BancoController::class, 'imprimirBancos']);
        Route::get('/admin/imprimirLicencas', [LicencaController::class, 'imprimirLicencas']);
        Route::get('/admin/imprimirUtilizador', [UserController::class, 'imprimirUtilizador']);
        Route::post('/admin/licencas/adicionar', [LicencaController::class, 'store']);
        Route::post('/admin/licencas/definirValor', [LicencaController::class, 'actualizarValorLicenca']);
        Route::put('/admin/licencas/update/{id}', [LicencaController::class, 'update']);
        Route::get('admin/licencas/deletar/{id}', [LicencaController::class, 'destroy']);


        Route::get('admin/licenca-empresa', [LicencaController::class, 'licencaPorEmpresaIndex']);

        //Coordernadas Bancaria
        Route::resource('admin/coordenadasbancaria', CoordernadaBancariaController::class);

        Route::get('admin/listar-pedidos', [AtivacaoLicencaController::class, 'index']);
        Route::get('admin/listarPedidoLicencas', [AtivacaoLicencaController::class, 'listarPedidoLicencas']);
        Route::get('admin/pedido-licencas/detalhes/{id}', [AtivacaoLicencaController::class, 'detalhes']);
        Route::post('admin/ativar-licenca/{id}', [AtivacaoLicencaController::class, 'ativarLicenca']);
        Route::post('admin/cancelar-licenca/{id}', [AtivacaoLicencaController::class, 'cancelarLicenca']);
        Route::get('/admin/imprimirPedidoLicenca/{pedidoId}', [AtivacaoLicencaController::class, 'imprimirPedidoLicenca']);
        Route::get('/admin/imprimirTodasPedidosLicencas', [AtivacaoLicencaController::class, 'imprimirTodasPedidosLicencas']);

        Route::resource('/admin/cliente-empresa', AdminEmpresaController::class);
        Route::get('/admin/imprimirClientes', [EmpresaController::class, 'imprimirClientes']);
        Route::get('admin/cliente-empresa/detalhes/{id}', [EmpresaController::class, 'detalhes']);
        Route::get('admin/cliente-empresa/{id}/delete', [EmpresaController::class, 'destroy']);

        //Permissions e Roles
        //   Route::get('admin/funcoes', [RoleController::class, 'index']);
        Route::get('admin/permissoes', [PermissionController::class, 'index']);
        //  Route::get('admin/funcao/{id}/permissions', [RoleController::class, 'listarPermissoes']);
        Route::get('admin/permission/{permissionId}/role', [PermissionController::class, 'listarRole']);
        Route::get('admin/permission/{permissionId}/delete', [PermissionController::class, 'destroy']);

        Route::get('/admin/taxaIva', [AdminTaxaIvaController::class, 'index']);
        Route::post('/admin/taxaIva/adicionar', [AdminTaxaIvaController::class, 'store']);
        Route::post('/admin/taxaIva/update/{codigo}', [AdminTaxaIvaController::class, 'update']);
        Route::get('/admin/taxaIva/deletar/{id}', [AdminTaxaIvaController::class, 'destroy']);


        Route::get('/admin/motivoIvaListar', [AdminMotivoIvaController::class, 'listar']);
        Route::get('/admin/motivoIva', [AdminMotivoIvaController::class, 'index']);
        Route::post('/admin/motivoIva/adicionar', [AdminMotivoIvaController::class, 'store']);
        Route::post('/admin/motivoIva/update/{id}', [AdminMotivoIvaController::class, 'update']);
        Route::get('/admin/motivoIva/deletar/{id}', [AdminMotivoIvaController::class, 'destroy']);


        Route::get('pagamentos/licenca', PagamentoLicencaIndexController::class)->name('pagamentoLicencasIndex');
        Route::get('pagamentos/licenca/novo', PagamentoLicencaCreateController::class)->name('pagamentoLicencaAdminNovo')->middleware('hasPermission:Gerir pedidos ativacao de licenca');

        Route::get('notificacoes', NotificacaoAvisoIndexController::class)->name('notificacaoDeAvisoIndex');
        Route::get('notificacao/novo', NotificacaoAvisoCreateController::class)->name('notificacaoDeAvisoCreate');

    });


    // Route::get('/admin/gerarSaft', [GerarSaftController::class, 'index']);
    // Route::get('/admin/gerarSaftXml', [GerarSaftController::class, 'xmlSaft']);
    // // Route::get('/empresa/gerarSaftXml', 'empresa\GerarSaftController@gerarSaft');
    // Route::get('gerarSaftXml', [GerarSaftController::class, 'gerarSaft']);
});


// ======================================================================================================================================
/**
 * ROTAS DO CLIENTE
 */

Route::get("TesteEmail", function () {
    dd(Hash::make('ato123'));
    $dado = [
        'email' => 'pauloggjoao@gmail.com',
        'senha' => 'mutue123'
    ];
    Mail::send(new MailCreateNewUser($dado));

    // JobCreateNewUser::dispatch($dado)->delay(now()->addSecond('5'));
});


Route::get("password/reset", [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
Route::post("password/reset", [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get("password/reset/{token}", [ResetPasswordController::class, 'showResetForm']);
Route::post("password/reset/{token}", [ResetPasswordController::class, 'reset'])->name("password.update");


Route::group(['middleware' => ['auth:empresa']], function () {
    Route::get('empresa/modulos/acesso', ModuloAcessoIndexController::class)->name('modulosAcesso.index');
    Route::group(['middleware' => ['ModulosAcesso']], function () {
        //PGC - Planos geral
        //Nota entrega
        Route::get('empresa/notaEntregas', NotaEntregaIndexController::class)->name('notaEntregaIndex');
        //Centro custo
        //Route::get('empresa/opcao/centros/custo', [OpcaoCentroCustoIndexController::class, 'index'])->name('opcaoCentroCusto.index');
        // Controlo de usuario
        Route::middleware(['updatePassword', 'UltimoAcesso'])->group(function () {

            //Assinaturas
            // Route::get('empresa/planos-assinaturas', [EmpresaLicencaController::class, 'index'])->name('planosAssinaturas');
            Route::get('empresa/assinaturas', AssinaturaIndexController::class)->name('assinaturas.index');

            //Permissão centro Custo
            Route::get('permissoes/centros/custo/{uuid}', PermissaoCentroCustoIndexController::class)->name('permissaoCentroCustoIndex');

            Route::middleware(['LicencaTerminado', 'ControlProduto'])->group(function () {
                //CUPONS DESCONTO
                Route::get('/empresa/cupons-desconto', CuponDescontoIndexController::class)->name('cuponDesconto.index');
                Route::get('/empresa/produtos/destaques', ProdutoDestaqueIndexController::class)->name('produtosDestaques.index');
                Route::get('/empresa/produto/destaque/novo', ProdutoDestaqueCreateController::class)->name('produtoDestaque.create');
                Route::get('/empresa/produto/destaque/editar/{uuid}', ProdutoDestaqueUpdateController::class)->name('produtoDestaque.edit');
                Route::get('/empresa/gerar/cupon/desconto', CuponDescontoCreateController::class)->name('cupon.create');

                //ANUNCIOS BANNER
                Route::get('/empresa/anuncios/banner', AnuncioBannerIndexController::class)->name('anunciosBanner.index');
                Route::get('/empresa/anuncios/banner/novo', AnuncioBannerCreateController::class)->name('anunciosBanner.novo');
                Route::post('/empresa/anuncios/banner/salvar', [AnuncioBannerCreateController::class, 'salvarNovoBanner'])->name('anunciosBanner.salvar');

                //PERGUNTAS FREQUENTES
                Route::get('/empresa/perguntas/frequente', PerguntaFrequenteIndexController::class)->name('perguntasFrequentes');
//            Route::get('/empresa/perguntas/frequente/novo', PerguntaFrequenteCreateController::class)->name('perguntasFrequentes.novo');
//            Route::post('/empresa/perguntas/frequente/salvar', [AnuncioBannerCreateController::class, 'salvarNovoBanner'])->name('perguntasFrequentes.salvar');
                // Route::get('/empresa/anuncios/banner/editar/{id}','AnuncioBannerCreateController@editarBanner')->name('editarBanner');

                Route::get('/empresa/anuncios/banner/editar/{id}', AnuncioBannerEditController::class)->name('banner.edit');

                //Pagamentos vendas online
                Route::get('/empresa/pagamentos/vendas/online', PagamentoVendaOnlineIndexController::class)->name('pagamentosVendaOnlineIndex');
                //Sem Permissão
                Route::get('/empresa/sempermissao', SemPermissaoController::class)->name('semPermissao.index');
                //Produtos
                Route::get('/empresa/produtos', ProdutoIndexController::class)->name('produtos.index');
                Route::get('/empresa/produtos/create', ProdutoCreateController::class)->name('produto.create')->middleware('hasPermission:gerir produtos');
                Route::get('/empresa/produto/novo', ProdutoCreateController::class)->name('produto.create')->middleware('hasPermission:gerir produtos');
                Route::post('/empresa/produto/store', [ProdutoStoreController::class, 'store'])->name('produto.store')->middleware('hasPermission:gerir produtos');
                Route::get('/empresa/produto/editar/{uuid}', ProdutoUpdateController::class)->name('produto.edit')->middleware('hasPermission:gerir produtos');
                Route::post('/empresa/produto/editar/{uuid}', [ProdutoUpdateController::class, 'update'])->name('produto.update')->middleware('hasPermission:gerir produtos');
                Route::get('/empresa/produto/descricao/{uuid}', ProdutoDescricaoController::class)->name('produto.carateristica')->middleware('hasPermission:gerir produtos');

                //Recibos
                Route::get('/empresa/recibos', ReciboIndexController::class)->name('recibo.index');
                Route::get('/empresa/recibo/novo', ReciboCreateController::class)->name('recibos.create')->middleware('hasPermission:gerir recibos');

                //Fecho caixa
                Route::get('/empresa/fecho-caixa', FechoCaixaIndexController::class)->name('fechoCaixa.index')->middleware('hasPermission:emitir fecho de caixa');

                //Manual utilizador
                Route::get('/manual-utilizador', ManualUtilizadorIndexController::class)->name('manual.index');

                Route::get('/empresa/relatorios-gerais', RelatorioGeralIndexController::class)->name('relatorio.relatoriosGeral');

                Route::get('/empresa/extrato/cliente', RelatorioExtratoClienteController::class)->name('extratoCliente')->middleware('hasPermission:emitir extrato do cliente');
                Route::post('empresa/ralarios-gerais/imprimir', [RelatorioGeralIndexController::class, 'imprimirRelatorioGeral']);
                //Nota Credito (Dar saldo ao cliente)
                Route::get('/empresa/notacredito', NotaCreditoIndexController::class)->name('notaCredito.index');
                Route::get('/empresa/notacredito/novo', NotaCreditoCreateController::class)->name('notaCredito.create')->middleware('hasPermission:gerir nota credito');

                //Nota debito (retirar saldo ao cliente)
                Route::get('/empresa/notadebito', NotaDebitoIndexController::class)->name('notaDebito.index');
                Route::get('/empresa/notadebito/novo', NotaDebitoCreateController::class)->name('notaDebito.create')->middleware('hasPermission:gerir nota debito');

                //Nota debito (retirar saldo ao cliente)
                Route::get('/empresa/anulacao/documento', AnulacaoDocumentoIndexController::class)->name('notaCreditoAnulacaoDoc.index');
                Route::get('/empresa/anulacao/documento/factura', AnulacaoDocumentoFacturaCreateController::class)->name('anulacaoDocumentoFactura.create')->middleware('hasPermission:gerir anular documento');
                Route::get('/empresa/anulacao/documento/recibo', AnulacaoDocumentoReciboCreateController::class)->name('anulacaoDocumentoRecibo.create')->middleware('hasPermission:gerir anular documento');

                Route::get('/empresa/retificacao/documento', RetificacaoDocumentoIndexController::class)->name('notaCreditoRetificacaoDoc.index');
                Route::get('/empresa/retificacao/documento/novo', RetificacaoDocumentoCreateController::class)->name('notaCreditoRetificacaoDoc.create')->middleware('hasPermission:gerir rectificar documento');

                // Route::get('/empresa/facturas/anulacao', [OperacaoController::class, 'anulacaoFacturaIndex']);
                Route::get('/empresa/facturas/rectificacao/novo', [OperacaoController::class, 'criarRectificacaoFactura']);
                //Clientes
                Route::get('/empresa/cliente/novo', ClienteCreateController::class)->name('clientes.create')->middleware('hasPermission:gerir clientes');
                Route::get('/empresa/clientes', ClienteIndexController::class)->name('clientes.index');
                Route::get('/empresa/cliente/editar/{clienteId}', ClienteUpdateController::class)->name('clientes.edit')->middleware('hasPermission:gerir clientes');
                Route::get('/empresa/cliente/detalhes/{clienteId}', ClienteShowController::class)->name('clientes.detalhes');
                Route::get('/empresa/cliente/extrato/{uuid}', ClienteExtratoController::class)->name('clientes.extrato');
                Route::get('/empresa/cartao/clientes', CartaoClienteIndexController::class)->name('cartaoClienteIndex');
                Route::get('/empresa/cartao/cliente/novo', CartaoClienteCreateController::class)->name('cartaoClienteCreate');
                Route::get('/empresa/bonus/cartao/cliente', BonusCartaoClienteIndexController::class)->name('bonusClienteIndex');

                //Fabricantes
                Route::get('/empresa/fabricantes', FabricanteIndexController::class)->name('fabricantes.index');
                Route::get('/empresa/fabricante/novo', FabricanteCreateController::class)->name('fabricantes.create')->middleware('hasPermission:gerir fabricantes');
                Route::get('/empresa/fabricante/editar/{fabricanteId}', FabricanteUpdateController::class)->name('fabricantes.edit')->middleware('hasPermission:gerir fabricantes');


                // Armazens
                Route::get('/empresa/armazens', ArmazemIndexController::class)->name('armazens.index');
                Route::get('/empresa/armazem/novo', ArmazemCreateController::class)->name('armazens.create')->middleware('hasPermission:gerir armazens');
                Route::get('/empresa/armazem/editar/{armazemId}', ArmazemUpdateController::class)->name('armazens.edit')->middleware('hasPermission:gerir armazens');

                // Bancos
                Route::get('/empresa/bancos', BancoIndexController::class)->name('bancos.index');
                Route::get('/empresa/banco/novo', BancoCreateController::class)->name('bancos.create')->middleware('hasPermission:gerir bancos');
                Route::get('/empresa/banco/editar/{bancoId}', BancoUpdateController::class)->name('bancos.edit')->middleware('hasPermission:gerir bancos');

                // Forma Pagamento
                Route::get('/empresa/formapagamento', FormaPagamentoIndexController::class)->name('formasPagamento.index');
                // Unidade medidas
                Route::get('/empresa/unidade-medidas', UnidadeMedidaIndexController::class)->name('unidadeMedidas.index');
                Route::get('/empresa/unidade-medida/novo', UnidadeMedidaCreateController::class)->name('unidadeMedidas.create');
                Route::get('/empresa/unidade-medida/editar/{unidadeMedidaId}', UnidadeMedidaUpdateController::class)->name('unidadeMedidas.edit');

                //PERFIS
                Route::get('empresa/perfis', RoleIndexController::class)->name('roles.index');
                Route::get('empresa/perfil/novo', RoleCreateController::class)->name('roles.create');
                Route::get('empresa/perfil/edit/{id}', RoleUpdateController::class)->name('roles.update');
                Route::get('empresa/perfil/{uuid}/permissoes', RolePermissoesIndexController::class)->name('roles.permissao')->middleware('hasPermission:gerir permissões');

                // Route::get('empresa/home', [EmpresaHomeController::class, 'index']);
                Route::get('empresa/usuarios', UsuarioIndexController::class)->name('users.index');
                Route::get('empresa/usuario/novo', UsuarioCreateController::class)->name('users.create')->middleware('hasPermission:gerir utilizadores');
                Route::get('empresa/usuario/edit/{uuid}', UsuarioUpdateController::class)->name('users.edit')->middleware('hasPermission:gerir utilizadores');
                Route::get('empresa/usuario/{uuid}/permissoes', UsuarioPermissoesIndexontroller::class)->name('users.permissions');


                Route::get('empresa/funcao/{id}/permissions', [EmpresaRoleController::class, 'listarPermissoes']);
                Route::get('empresa/permission/{permissionId}/role', [EmpresaPermissionController::class, 'listarRole']);

                //Fornecedor
                Route::get('/empresa/fornecedores', FornecedorIndexController::class)->name('fornecedores.index');
                //Route::get('/empresa/forn', FornecedorIndexController::class)->name('fornecedores.index');
                Route::get('/empresa/fornecedor/novo', FornecedorCreateController::class)->name('fornecedores.create')->middleware('hasPermission:gerir fornecedores');
                Route::get('/empresa/fornecedor/editar/{fornecedorId}', FornecedorUpdateController::class)->name('fornecedores.edit')->middleware('hasPermission:gerir fornecedores');
                Route::get('/empresa/fornecedor/detalhes/{fornecedorId}', FornecedorShowController::class)->name('fornecedores.detalhes');

                //Empresa
                Route::get('empresa/configuracao', EmpresaUpdateController::class)->name('empresa.edit')->middleware('hasPermission:gerir empresa');
                // Route::get('empresa/configuracao', [EmpresaController::class, 'edit'])->name('empresa.edit');


                //Definir numeração de sequencia documentos
                Route::get('empresa/numeracao/documentos', SequenciaDocumentoIndexController::class)->name('sequenciaDocumento.index');

                //Marcas
                Route::get('/empresa/marcas', MarcaIndexController::class)->name('marcas.index');
                Route::get('/empresa/marca/novo', MarcaCreateController::class)->name('marcas.create')->middleware('hasPermission:gerir marcas');
                Route::get('/empresa/marca/editar/{marcaId}', MarcaUpdateController::class)->name('marcas.edit')->middleware('hasPermission:gerir marcas');
                //Categorias
                Route::get('/empresa/categorias', CategoriaIndexController::class)->name('categorias.index');
                Route::get('/empresa/categoria/novo', CategoriaCreateController::class)->name('categorias.create')->middleware('hasPermission:gerir categoria');
                Route::get('/empresa/categoria/editar/{categoriaId}', CategoriaUpdateController::class)->name('categorias.edit')->middleware('hasPermission:gerir categoria');
                Route::get('/empresa/categoria/addsub/{categoriaId}', CategoriaUpdateSubCategoriaController::class)->name('categorias.addSubCategoria')->middleware('hasPermission:gerir categoria');

                //Fretes municipios
                Route::get('/empresa/municipiosfrete', MunicipioFreteIndexController::class)->name('municipiosFrete.index');
                Route::get('/empresa/municipiosfrete/novo', MunicipioFreteCreateController::class)->name('municipiosFrete.create');
                Route::get('/empresa/municipiosfrete/editar/{municipioId}', MunicipioFreteUpdateController::class)->name('municipiosFrete.update');

                //Fretes comunas
                Route::get('/empresa/comunasFrete', ComunaFreteIndexController::class)->name('comunasFrete.index');
                Route::get('/empresa/comunasFrete/novo', ComunaFreteCreateController::class)->name('comunasFrete.create');
                Route::get('/empresa/comunasFrete/editar/{comunaId}', ComunaFreteUpdateController::class)->name('comunasFrete.update');

                // Permissoes
                Route::get('/empresa/permissoes', PermissaoIndexController::class)->name('permissoes.index');

                // Modelos documentos
                Route::get('empresa/modelo-documentos', ModeloDocumentoController::class)->name('modeloDocumento.index')->middleware('hasPermission:editar modelo documento');

                // Facturas
                Route::get('empresa/facturas-proformas', FacturaProformaIndexController::class)->name('facturasProformaIndex')->middleware('hasPermission:converter proforma');

                // Route::post('/empresa/produto/store', [ProdutoStoreController::class, 'store'])->name('produto.store')->middleware('hasPermission:gerir produtos');
                // Route::get('/empresa/produto/editar/{uuid}', ProdutoShowController::class)->name('produto.edit')->middleware('hasPermission:gerir produtos');
                // Route::post('/empresa/produto/editar/{uuid}', [ProdutoUpdateController::class, 'update'])->name('produto.update')->middleware('hasPermission:gerir produtos');


                Route::get('empresa/centro-custos', CentroCustoIndexController::class)->name('centroCusto.index');
                Route::get('empresa/centro/custos', CentroCustoRelatorioIndexController::class)->name('centroCustosIndex');
                // Route::get('empresa/centro/custos', [CentroCustoController::class, 'centroCustosIndex'])->name('centroCustosIndex');
                Route::get('empresa/centro/custos/novo', CentroCustoCreateController::class)->name('centroCusto.create')->middleware('hasPermission:gerir centro de custo');
                Route::get('empresa/centro/custo/editar/{uuid}', CentroCustoUpdateController::class)->name('centroCusto.update')->middleware('hasPermission:gerir centro de custo');
                // Route::get('empresa/centro/custos/editar/{uuid}', [CentroCustoController::class, 'edit'])->name('centroCusto.edit');
                // Route::get('empresa/centro/custos/novo', [CentroCustoController::class, 'create'])->name('centroCusto.create');
                // Route::post('empresa/centro/custos/novo', [CentroCustoController::class, 'store'])->name('centroCusto.store');
                // Route::put('empresa/centro/custos/editar/{uuid}', [CentroCustoController::class, 'update'])->name('centroCusto.update');

                //Minhas licenças
                Route::get('empresa/minhas-licencas', MinhaLicencaController::class)->name('minhasLicencas');
                // Route::get('empresa/minhas-licencas', [EmpresaLicencaController::class, 'minhasLicencas']);


                //Vendas
                Route::get('/empresa/vendas-diaria', VendasDiariaIndexController::class)->name('indexVendaDiaria');
                Route::get('/empresa/vendas-mensal', VendasMensalIndexController::class)->name('indexVendaMensal');


                //entrada estoque
                Route::get('/empresa/produtos/entrada', EntradaProdutoIndexController::class)->name('entradasProdutosIndex');
                Route::get('/empresa/produtos/entrada/novo', EntradaProdutoCreateController::class)->name('entradasProdutosCreate');
                // Route::get('/empresa/produtos/entrada', [OperacaoController::class, 'entradaProdutoStockIndex']);

                // Route::get('/empresa/produtos/entrada/novo', [OperacaoController::class, 'entradaProdutoStockNovo'])->name('entradasProdutosCreate')->middleware('hasPermission:gerir entrada produto');
                Route::get('/empresa/produto/actualizar-stock', [ExistenciaStockController::class, 'index']);
                Route::get('/empresa/produto/atualizacao/estoque', AtualizacaoEstoqueIndexController::class)->name('atualizarStockIndex');;
                Route::get('/empresa/produto/atualizacao/estoque/novo', AtualizacaoEstoqueCreateController::class)->name('atualizarStockCreate');


                // Route::get('/empresa/vendas-mensal', [VendaController::class, 'indexVendaMensal']);

                // Route::get('/empresa/vendas-diaria', [VendaController::class, 'indexVendaDiaria']);


                //Relatórios
                Route::post('empresa/centro/custo/{uuid}/relatorio-stock/imprimir', [RelatorioController::class, 'printRelatorioStock'])->name('relatorioEstoque');
                Route::get('empresa/centro/custo/{uuid}/relatorio-existenciastock/imprimir', [RelatorioController::class, 'printRelatorioExistenciaStock'])->name('relatorioExistenciaStock');
                Route::post('empresa/centro/custo/{uuid}/relatorio-existenciavenda/imprimir', [RelatorioController::class, 'printRelatorioVenda'])->name('relatorioVenda');
                // Route::get('empresa/centro/custo/{uuid}/relatorios', RelatorioController::class)->name('relatorio.index')->middleware('hasPermission:visualizar relatorio por centro custo');;

                Route::get('empresa/relatorios', RelatorioController::class)->name('relatorio.index')->middleware('hasPermission:visualizar relatorios');;


                Route::get('empresa/vendas', [PdvController::class, 'index'])->name('empresa.vendas');
                Route::get('empresa/vendas/listarProdutosVendas', [ProdutoController::class, 'listarProdutosVendas']);
                Route::get('empresa/vendas/listarDocumentoVenda', [TipoDocumentoController::class, 'listarDocumentoVenda']);
                Route::get('empresa/vendas/listarFormaPagamentoVendas', [FormaPagamentoController::class, 'listarFormaPagamentoVendas']);
                Route::get('/empresa/vendas/listarClientesInputFactura', [ClienteController::class, 'listarClientesInputFactura']);
                Route::get('empresa/vendas/pegarClienteDiverso', [ClienteController::class, 'pegarClienteDiverso']);
                Route::post('empresa/vendas/finalizarVenda', [FinalizarVendaController::class, 'store']);
                Route::get('/imprimirFactura', [FacturaController::class, 'imprimirFactura']);
                Route::get('/imprimirFacturaPdv1', [FacturaController::class, 'imprimirFacturaPdv1']);
                Route::get('/reimprimirFactura', [FacturaController::class, 'reimprimirFactura']);
                Route::get('/imprimirFacturaTicket', [FacturaController::class, 'imprimirFacturaTicket']);
                Route::post('empresa/fechocaixaVenda/imprimir', [FechoCaixaController::class, 'imprimirFechoCaixaVenda']);
                Route::post('empresa/pedido-licenca/{id}', [EmpresaLicencaController::class, 'pedidoAtivacaoLicenca']);
                Route::get('/empresa/planos-assinaturas/pegar-dependecias', [EmpresaLicencaController::class, 'pegarDependencias']);
                // Route::get('empresa/planos-assinaturas', 'empresa\LicencaController@index');
                Route::post('/empresa/planos-assinaturas/salvar-factura', [LicencaController::class, 'salvarPedidoFactura']);

                Route::post('/empresa/planos-assinaturas/salvar-pagamento', [LicencaController::class, 'salvarPagamento']);
                Route::get('/empresa/planos-assinaturas/imprimir-factura/{id}', [LicencaController::class, 'imprimirFactura']);
                Route::get('/empresa/planos-assinaturas/imprimir-recibo-pagamento/{id}/{tipoFactura}', [LicencaController::class, 'imprimirReciboPagamento']);
                Route::get('/empresa/planos-assinaturas/buscar-referencia-factura/{faturaRereference}', [LicencaController::class, 'buscarReferenciaFactura']);

                //Configurações
                Route::get('empresa/parametros', ParametroIndexController::class)->name('parametrosIndex')->middleware('hasPermission:definir parametros');
                Route::get('empresa/definir-parametros', [EmpresaConfiguracaoController::class, 'index'])->middleware('hasPermission:definir parametros');
                Route::post('empresa/impressao/editar', [EmpresaConfiguracaoController::class, 'show']);
                Route::post('empresa/retencao/atualizarRetencao', [EmpresaConfiguracaoController::class, 'actualizarRetencao']);
                Route::post('empresa/desconto/atualizarDesconto', [EmpresaConfiguracaoController::class, 'atualizarDesconto']);
                Route::post('empresa/impressao/adicionar', [EmpresaConfiguracaoController::class, 'atualizarTipoImpressao']);
                Route::post('empresa/viaImpressao/adicionar', [EmpresaConfiguracaoController::class, 'atualizarNumeroViaImpressao']);
                //Converter proformas
                Route::get('empresa/converter/proformas', ProformaIndexController::class)->name('proformas.index')->middleware('hasPermission:converter proforma');
                //Anulação de documentos
                Route::get('empresa/documentos/notacreditos', AnulacaoDocumentoFaturaIndexController::class)->name('notacreditos.index');
                Route::get('empresa/documentos/retificacao/faturas', RetificacaoDocumentoFaturaIndexController::class)->name('retificacaoDocumentoFatura.index');

                Route::get('empresa/retificacao/fatura/novo/{id}', RetificacaoDocumentoFaturaCargaCreateController::class)->name('retificacaoFaturaCargaCreate');
                Route::get('empresa/retificacao/fatura/outro/servico/{faturaId}', RetificacaoDocumentoFaturaOutroServicoCreateController::class)->name('retificacaoFaturaOutroServicoCreate');
                Route::get('empresa/anulacao/fatura/novo/{id}', AnulacaoDocumentoFaturaCreateController::class)->name('anulacaoFaturaCargaCreate')->middleware('hasPermission:anulacao documentos');
                Route::get('empresa/anulacao/fatura/comercial/{id}', AnulacaoDocumentoFaturaComercialCreateController::class)->name('anulacaoFaturaServicoComercialCreate')->middleware('hasPermission:anulacao documentos');
                Route::get('empresa/anulacao/fatura/outro/servico/{id}', AnulacaoDocumentoFaturaOutroServicoCreateController::class)->name('anulacaoFaturaOutroServicoCreate')->middleware('hasPermission:anulacao documentos');
                Route::get('empresa/retificacao/fatura/aeroportuario/novo/{faturaId}', RetificacaoDocumentoFaturaAeroportuarioCreateController::class)->name('retificacaoFaturaAerportuarioCreate');
                Route::get('empresa/retificacao/fatura/servico/comercial/{faturaId}', RetificacaoDocumentoFaturaServicoComercialCreateController::class)->name('retificacaoFaturaServicoComercialCreate');


                Route::get('empresa/documentos/anulado/recibos', AnulacaoDocumentoReciboIndexController::class)->name('anulacaoDocumentoRecibo.index');
                Route::get('empresa/anulacao/recibo/novo', \App\Http\Controllers\empresa\Operacao\AnulacaoDocumentoReciboCreateController::class)->name('recibosAnulados.create')->middleware('hasPermission:anulacao documentos');

                Route::get('/empresa/relatorios-mapa-faturacao', RelatorioGeralIndexController::class)->name('relatorio.mapaFaturacao')->middleware('hasPermission:imprimir mapa faturacao');

                Route::get('/empresa/relatorios-gerais', RelatorioGeraisTodosController::class)->name('relatorio.gerais')->middleware('hasPermission:imprimir mapa faturacao');
                Route::post('empresa/alterarDiasVencimentoFactura', [EmpresaConfiguracaoController::class, 'alterarDiasVencimentoFactura']);
                Route::post('empresa/alterarDiasVencimentoFtProforma', [EmpresaConfiguracaoController::class, 'alterarDiasVencimentoFtProforma']);
                Route::post('empresa/alterarSerieDocumento', [EmpresaConfiguracaoController::class, 'alterarSerieDocumento']);
                Route::post('empresa/alterarObservacaoFactura', [EmpresaConfiguracaoController::class, 'alterarObservacaoFactura']);


                //Fecho de caixa
                Route::post('empresa/fechocaixa/imprimir', [FechoCaixaController::class, 'imprimirFechoCaixa']);


                //notificações
                Route::get('notifications', [EmpresaNotificacaoController::class, 'notifications']);
                Route::get('empresa/notificationsAll', [EmpresaNotificacaoController::class, 'notificationsAll']);
                Route::get('empresa/listar-notificacoes', [EmpresaNotificacaoController::class, 'listarNotificacoes']);
                Route::put('empresa/notificationsRead', [EmpresaNotificacaoController::class, 'markAsRead']);
                Route::get('empresa/notifications/deletar/{id}', [EmpresaNotificacaoController::class, 'deletar']);

                //Página inicial do cliente
                Route::put('empresa/usuarios/{id}/update', [UserController::class, 'update']);
                Route::get('empresa/usuarios/{id}/delete', [UserController::class, 'destroy']);
                Route::get('empresa/usuario/perfil', [UserController::class, 'perfilUtilizador']);
                Route::post('empresa/usuario/updateSenha/{userId}', [UserController::class, 'updateSenha']);
                Route::get('/fichaCadastro', [UserController::class, 'visualizarFichaCadastro']);


                Route::post('empresa/configuracao/update/{id}', [EmpresaController::class, 'update'])->name('empresa.update');

                Route::post('empresa/setar-modelo-documento', [EmpresaConfiguracaoController::class, 'setarModeloDocumento']);

                Route::get('/empresa/pagamento/fornecedor', [FornecedorController::class, 'pagamentoFacturaFornecedor'])->middleware('hasPermission:gerir pagamento fornecedor');

                Route::get('/empresa/imprimirPagamentoFornecedor/{pagamentoId}/{viaImpressao}', [FornecedorController::class, 'imprimirPagamentoFornecedor']);
                Route::post('/empresa/pagamentoFacturaFornecedor', [FornecedorController::class, 'pagamentoFornecedor']);
                Route::get('/empresa/buscarDividaRestante/{entradaProdutoId}/{fornecedorId}', [FornecedorController::class, 'buscarDividaRestante']);
                Route::get('/empresa/listarFornecedores', [FornecedorController::class, 'listarFornecedores']);
                Route::get('/empresa/listarFacturasFornecedores/{fornecedorId}', [FornecedorController::class, 'listarFacturasFornecedores']);
                Route::get('/empresa/pagamentoEfectuadosFacturaFornecedor', [FornecedorController::class, 'listarPagamentosFacturasFornecedores']);

                Route::get('/empresa/fornecedores/adicionar', [FornecedorController::class, 'create']);
                Route::post('/empresa/fornecedores/adicionar', [FornecedorController::class, 'store']);
                Route::get('/empresa/fornecedorImprimir', [FornecedorController::class, 'imprimirFornecedores']);
                Route::get('/empresa/fornecedores/detalhes/{id}', [FornecedorController::class, 'detalhes']);
                Route::get('/empresa/fornecedores/editar/{id}', [FornecedorController::class, 'edit']);
                Route::post('empresa/fornecedores/update/{id}', [FornecedorController::class, 'update']);
                Route::get('empresa/fornecedores/deletar/{id}', [FornecedorController::class, 'destroy']);
                Route::get('/empresa/imprimir/fornecedores', [ReportController::class, 'imprimirFornecedores']);

                Route::get('/empresa/clientes/create', [ClienteController::class, 'create']);
                Route::post('/empresa/clientes/adicionar', [ClienteController::class, 'store']);
                Route::post('/empresa/cliente/imprimirExtratoConta', [ClienteController::class, 'imprimirExtratoConta']);
                Route::get('/empresa/clientes/detalhes/{id}', [ClienteController::class, 'detalhes']);
                Route::get('/empresa/clientes/editar/{id}', [ClienteController::class, 'edit']);
                Route::post('/empresa/clientes/update/{id}', [ClienteController::class, 'update']);
                Route::get('/empresa/clientes/deletar/{id}', [ClienteController::class, 'destroy']);
                Route::get('/empresa/clientes/{query}', [ClienteController::class, 'show']);
                Route::get('/empresa/pegar-dependecias', [ClienteController::class, 'pegarDependencias']);
                Route::get('/empresa/clientes/saldoActual/{idCliente}', [ClienteController::class, 'PegarSaldoCliente']);


                // ROTAS USADO NO VUE
                Route::get('/empresa/listarClienteFacturacao', [ClienteController::class, 'listarClientes']);
                Route::get('/empresa/clientes-filtro/{status_id}/{pais_id}', [ClienteController::class, 'listarClienteFiltro']);
                Route::get('/empresa/imprimir-clientes', [ClienteController::class, 'imprimirClientes']);

                // Gestores
                Route::get('/empresa/gestores', [GestorController::class, 'index']);
                Route::post('/empresa/gestores/adicionar', [GestorController::class, 'store']);
                Route::post('/empresa/gestores/update/{id}', [GestorController::class, 'update']);
                Route::get('/empresa/gestores/deletar/{id}', [GestorController::class, 'destroy']);
                Route::get('/empresa/gestores/pegar-dependecias', [GestorController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir/gestores', [ReportController::class, 'imprimirGestores']);

                Route::get('/empresa/armazens/pegar-dependecias', [ArmazenController::class, 'pegarDependencias']);
                // Categorias
                Route::post('/empresa/categorias/adicionar', [CategoriaController::class, 'store']);
                Route::post('/empresa/categorias/update/{id}', [CategoriaController::class, 'update']);
                Route::get('/empresa/categorias/deletar/{id}', [CategoriaController::class, 'destroy']);
                Route::get('/empresa/categorias/pegar-dependecias', [CategoriaController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir/categorias', [CategoriaController::class, 'imprimirCategorias']);

                // Marcas
                Route::post('/empresa/marcas/adicionar', [MarcaController::class, 'store']);
                Route::post('/empresa/marcas/update/{id}', [MarcaController::class, 'update']);
                Route::get('/empresa/marcas/deletar/{id}', [MarcaController::class, 'destroy']);
                Route::get('/empresa/marcas/pegar-dependecias', [MarcaController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir/marcas', [MarcaController::class, 'imprimirMarcas']);

                Route::get('/empresa/unidades/pegar-dependecias', [UnidadeController::class, 'pegarDependencias']);

                // Classes de Bem
                Route::get('/empresa/classes', [ClasseController::class, 'index']);
                Route::post('/empresa/classes/adicionar', [ClasseController::class, 'store']);
                Route::post('/empresa/classes/update/{id}', [ClasseController::class, 'update']);
                Route::get('/empresa/classes/deletar/{id}', [ClasseController::class, 'destroy']);
                Route::get('/empresa/classes/pegar-dependecias', [ClasseController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir/classes', [ClasseController::class, 'imprimirClasses']);


                // Forma de Pagamento
                Route::get('/empresa/listarFormaPagamento', [FormaPagamentoController::class, 'listarFormaPagamento']);
                Route::post('/empresa/formapagamento/adicionar', [FormaPagamentoController::class, 'store']);
                Route::post('/empresa/formapagamento/update/{id}', [FormaPagamentoController::class, 'update']);
                Route::get('/empresa/formapagamento/deletar/{id}', [FormaPagamentoController::class, 'destroy']);
                Route::get('/empresa/formapagamento/pegar-dependecias', [FormaPagamentoController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir/formapagamento', [FormaPagamentoController::class, 'imprimirFormaPagamento']);

                // Produtos
                // Route::get('/empresa/produtos-mais-vendidos', [ProdutoController::class, 'indexMaisVendidos'])->middleware('hasPermission:visualizar produtos mais vendidos');
                Route::get('/empresa/produtos-mais-vendidos', ProdutoMaisVendidosIndexController::class)->middleware('hasPermission:visualizar produtos mais vendidos');
                Route::get('empresa/listarProdutos', [ProdutoController::class, 'listarProdutos']);
                Route::get('empresa/listarProdutosPorArmazem', [ProdutoController::class, 'listarProdutosPorArmazem']);
                Route::post('/empresa/produtos/editar/{id}', [ProdutoController::class, 'update']);

                Route::get('/centro-de-custo', [EmpresaController::class, 'centrodeCusto']);

                Route::get('/empresa/produtos/deletar/{id}', 'empresa\ProdutoController@destroy');

                Route::post('/empresa/produtos/adicionar', [ProdutoController::class . 'store']);
                Route::get('/empresa/produtos/listarArmazens', [ProdutoController::class, 'listarArmazens']);
                Route::get('/empresa/pegar-dependecias', [ProdutoController::class, 'pegarDependencias']);
                Route::get('/empresa/imprimir-produtos', [ProdutoController::class, 'imprimirProdutos']);

                //LISTAR PRODUTOS EM ESTOQUE
                Route::get('/empresa/produtos/stock', [ProdutoController::class, 'stock'])->middleware('hasPermission:visualizar existencia estoque produto');;
                Route::get('/empresa/produtos/quantidade/critica', [ProdutoController::class, 'listar_Quantidade_critica']);

                Route::get('/empresa/categorias/adicionar', [CategoriaController::class, 'create']);
                Route::post('/empresa/categorias/adicionar', [CategoriaController::class, 'store']);

                //Tipos de mercadorias
                Route::get('/empresa/mercadorias', MercadoriaIndexController::class);

                //Especificao de mercadorias
                Route::get('/empresa/mercadorias/especificacao', EspecificacaoMercadoriaController::class);

                //Logs de acesso
                Route::get('/empresa/logs/acesso', LogAcessoIndexController::class)->name('logAcessoIndex')->middleware('hasPermission:visualizar logs de acesso');

                //Câmbio
                Route::get('/empresa/cambio', CambioController::class);

                //IntervaloPmd
                Route::get('/empresa/taxas/pmd', \App\Http\Controllers\empresa\Taxas\IntervaloPmdController::class);

                Route::get('empresa/facturacao', [FacturacaoController::class, 'index']);
                Route::put('empresa/facturacao/produto/editar', [FacturacaoController::class, 'editarProduto']);
                Route::get('empresa/facturacao/imprimir-lista-vendas', [FacturacaoController::class, 'imprimirListaVenda']);
//            Route::get('empresa/faturacao/novo', [FacturacaoController::class, 'create']);
//            Route::get('empresa/faturacao/novo', [FaturacaoIndexController::class, 'create']);
                Route::get('empresa/faturacao/novo', FaturacaoCreateController::class);
                Route::get('empresa/emissao/fatura/carga', EmissaoFaturaCargaController::class)->middleware('hasPermission:emitir fatura carga');
                Route::get('empresa/emissao/fatura/aeronautica', EmissaoFaturaAeronauticoController::class)->middleware('hasPermission:emitir fatura aeronautico');
                Route::get('empresa/emissao/fatura/outros/servicos', EmissaoFaturaOutroServicoController::class)->middleware('hasPermission:emitir fatura outros servicos');
                Route::get('empresa/emissao/fatura/servicos/comerciais', EmissaoFaturaServicoComercialController::class)->middleware('hasPermission:emitir fatura servicos comerciais');


                Route::post('empresa/facturacao/salvar', [FacturacaoController::class, 'store']);
                Route::post('empresa/emitirDocumento', [EmitirDocumentoController::class, 'store']);
                Route::get('empresa/facturacao/produtos/{armazen_id}', [FacturacaoController::class, 'listarProdutos']);
                Route::get('empresa/facturacao/produtoQtdExistenciaStock/{id}/{quant}', [FacturacaoController::class, 'listarQtdProdutoStock']);

                Route::get('empresa/facturacao/cliente_default', [FacturacaoController::class, 'listarClienteDefault']);

                //Listar facturas
                Route::get('empresa/facturas/cargas', FacturasIndexController::class)->name('facturas.index');
                Route::get('empresa/facturas/aeroportuario', FacturasAeroportuarioIndexController::class)->name('facturasAeroportuario.index');
                Route::get('empresa/facturas/outros/servicos', FacturasOutroServicoIndexController::class)->name('facturasOutrosServico.index');
                Route::get('empresa/facturas/servico/comerciais', FacturasServicoComercialIndexController::class)->name('facturasServicoComercial.index');

                Route::get('empresa/listarFacturas', [FacturacaoController::class, 'listarFacturasApi']);
                Route::get('empresa/facturasCliente/{clienteId}', [FacturaController::class, 'listarFacturasPorCliente']);
                Route::get('empresa/facturas-licencas', [FacturaController::class, 'facturasLicencasIndex']);
                Route::get('empresa/recibos-facturas-licenca', [FacturaController::class, 'reciboFacturaLincencaIndex']);
                Route::get('empresa/recibosFacturasLicenca/{reciboFacturaLicencaId}', [FacturaController::class, 'imprimirReciboLicenca']);
                Route::get('empresa/searchFacturas', [FacturaController::class, 'searchFacturas']);
                Route::get('empresa/searchRecibos', [FacturaController::class, 'searchRecibos']);
                Route::post('empresa/converterFacturaProforma/salvar', [FacturaController::class, 'converterFacturaProforma']);

                Route::get('empresa/facturacao/imprimir-factura/{id}', [FacturacaoController::class, 'reimprimirFactura']);
                Route::get('empresa/facturacao/imprimir-factura/{id}/{tipoFactura}', [FacturacaoController::class, 'imprimirFactura']);
                Route::get('empresa/factura/imprimir-factura-anulada/{id}', [FacturacaoController::class, 'imprimirFacturaAnulada']);

                Route::get('empresa/facturacao/formaPagamentos', [FacturacaoController::class, 'listarFormaPagamentos']);
                Route::get('empresa/facturacao/armazens', [FacturacaoController::class, 'listarArmazens']);


                //todas as rotas de operaçoes
                Route::get('empresa/facturas/operacao-deposito-recibo', [OperacaoController::class, 'depositoReciboIndex']);
                Route::get('empresa/imprimirRecibo/{id}', [OperacaoController::class, 'imprimirRecibo']);
                Route::get('empresa/imprimirTodosRecibos', [OperacaoController::class, 'imprimirTodasRecibos']);

                Route::get('empresa/facturas/recibo/novo', [OperacaoController::class, 'createRecibo']);
                Route::get('/empresa/recibo/ListarClientesComFacturasRecibo', [OperacaoController::class, 'ListarClientesComFacturasRecibo']);
                Route::get('/empresa/recibo/ListarClientesComFacturas', [OperacaoController::class, 'ListarClientesComFacturas']);
                Route::get('/empresa/recibo/verificaAplicacaoRetencaoRecibo/{facturaId}', [OperacaoController::class, 'verificaAplicacaoRetencaoRecibo']);

                Route::get('/empresa/comparaDataDocumento', [OperacaoController::class, 'comparaDataDocumentoAnteriorComAtual']);

                Route::get('empresa/factura/{id}', [OperacaoController::class, 'listarFactura']);

                //rotas de vendas por produtos, diaria, mensal
                Route::get('/empresa/vendas-produtos', [VendaController::class, 'indexVendaProduto']);
                Route::get('/empresa/vendas-produto/{id}', [VendaController::class, 'imprimirVendasPorProdutos']);
                Route::get('/empresa/relatorios-vendas', [VendaController::class, 'indexRelatoriosVendas']);
                Route::get('/empresa/listarVendasMensal', [HomeController::class, 'listarVendasMensal']);
                Route::get('/empresa/movimento/diario', [VendaController::class, 'movimentoDiario']);
                Route::post('/empresa/imprimirMovimentoDiario', [VendaController::class, 'imprimirMovimentoDiario']);
                Route::get('/empresa/imprimirRelatorioDiario', [VendaController::class, 'imprimirRelatorioDiario']);
                Route::get('/empresa/imprimirRelatorioMensal', [VendaController::class, 'imprimirRelatorioMensal']);
                Route::get('/empresa/imprimirRelatorioAnual', [VendaController::class, 'imprimirRelatorioAnual']);
                Route::get('/empresa/imprimirTodosRelatorioDiarioPorFuncionario', [VendaController::class, 'imprimirTodosRelatorioDiarioPorFuncionario']);
                Route::get('/empresa/imprimirTodosRelatorioDiario', [VendaController::class, 'imprimirTodosRelatorioDiarioPorFuncionario']);
                Route::get('/empresa/imprimirRelatorioAnualTodoFuncioario', [VendaController::class, 'imprimirRelatorioAnualTodoFuncioario']);
                Route::get('/empresa/imprimirRelatorioMensalTodoFuncionario', [VendaController::class, 'imprimirRelatorioMensalTodoFuncionario']);

                Route::get('/empresa/facturacao-diaria', [VendaController::class, 'listaFacturacaoDiaria']);
                Route::get('/empresa/facturacao-diaria/{data}', [VendaController::class, 'imprimirVendasDiaria']);
                Route::get('/empresa/facturas-mensal', [VendaController::class, 'listaFacturacaoMensal']);
                Route::get('/empresa/facturas-mensalImprimir', [VendaController::class, 'imprimirVendasMensal']);

                //todas as rotas de operaçoes
                Route::get('/empresa/facturas/operacao-deposito-recibo', [OperacaoController::class, 'depositoReciboIndex']);

                Route::get('/empresa/taxaIvaListar', [TaxaIvaController::class, 'listarTaxas']);
                Route::get('/empresa/taxaIva', [TaxaIvaController::class, 'index']);
                Route::post('/empresa/taxaIva/adicionar', [TaxaIvaController::class, 'store']);
                Route::post('/empresa/taxaIva/update/{codigo}', [TaxaIvaController::class, 'update']);
                Route::get('/empresa/taxaIva/deletar/{id}', [TaxaIvaController::class, 'destroy']);

                Route::get('/empresa/motivoIva', [MotivoIvaController::class, 'index']);
                Route::post('/empresa/motivoIva/adicionar', [MotivoIvaController::class, 'store']);
                Route::post('/empresa/motivoIva/update/{id}', [MotivoIvaController::class, 'update']);
                Route::get('/empresa/motivoIva/deletar/{id}', [MotivoIvaController::class, 'destroy']);
                Route::get('/empresa/motivoIvaListar/{regimeEmpresa}', [MotivoIvaController::class, 'listar']);

                //Gerar Saft
                Route::get('/empresa/gerarSaft', GerarSaftController::class)->middleware('hasPermission:emitir ficheiro saft');
                Route::get('/empresa/gerarSaftXml', [GerarSaftController::class, 'gerarSaft'])->middleware('hasPermission:emitir ficheiro saft');
                //STOCK

                Route::post('empresa/entradaStock', [StockController::class, 'entradaStock']);
                Route::get('empresa/imprimirEntradaStock/{entradaId}', [StockController::class, 'imprimirEntradaStock']);
                Route::get('empresa/produtos/existenciaStock', [ExistenciaStockController::class, 'listarExistenciaStock']);
                Route::get('empresa/produtos/actualizar/novo', [ExistenciaStockController::class, 'actualizarProdutoStockNovo'])->middleware('hasPermission:gerir actualizar estoque');
                Route::get('empresa/produtos/imprimirActualizacaoStock/{actualizaStockId}', [ExistenciaStockController::class, 'imprimirActualizacaoStock']);
                Route::get('empresa/produtos/existenciaStock/{produtoId}/{armazemId}', [ExistenciaStockController::class, 'listarProdutoExistenciaStock']);
                Route::get('empresa/produtos/imprimir/existenciaStock', [ExistenciaStockController::class, 'imprimirExistenciaStock']);
                Route::get('empresa/produtos/imprimir/existenciaStock/{existenciaId}', [ExistenciaStockController::class, 'imprimirExistenciaStocksPorId']);
                Route::post('empresa/produtos/actualizarStock', [ExistenciaStockController::class, 'actualizarStock']);

                Route::get('empresa/produtos/transferencia', TransferenciaStockController::class);
//            Route::get('empresa/produtos/transferencia', [StockController::class, 'transferenciaIndex']);
                Route::get('empresa/produtos/transferencia/novo', [StockController::class, 'transferenciaNovo'])
                    ->name('transferenciaNova')
                    ->middleware('hasPermission:gerir transferir produto');
                Route::post('empresa/produtos/transferencia/salvar', [StockController::class, 'transferenciaSalvar']);
                Route::get('empresa/produtos/transferencia/imprimir/{id}', [StockController::class, 'transferenciaImprimir']);


                //forma de pagamento
                Route::get('/empresa/tipoFormaPagamentos', [PagamentoController::class, 'listarTipoFormaPagamentos']);
                Route::get('/empresa/formasPagamentosGeral', [PagamentoController::class, 'listarFormasPagamentosGeral']);

                Route::get('/empresa/roles-permissions', [RolesPermissionController::class, 'rolesPermissions']);

                //Emitir recibos
                Route::post('/empresa/recibo/salvar', [OperacaoController::class, 'emitirRecibo']);

                //Operações
                Route::get('/empresa/facturas/rectificacao', [OperacaoController::class, 'rectificacaoFacturaIndex']);
                Route::get('/empresa/facturas/anulacao', [OperacaoController::class, 'anulacaoFacturaIndex']);
                Route::get('/empresa/facturas/anulacao/novo', [OperacaoController::class, 'criarAnulacaoFactura']);
                Route::get('/empresa/factura/anulacacao/listarTipoDocumentos', [OperacaoController::class, 'listarTipoDocumentos']);
                Route::get('/empresa/factura/anulacacao/listarFacturasRefDocumentoEcliente/{TipoDoc}/{idCliente}', [OperacaoController::class, 'listarFacturasRefDocumentoEcliente']);

                //Anulação de recibos
                Route::get('/empresa/recibos/anulacao/novo', [OperacaoController::class, 'criarAnulacaoRecibo']);
                Route::post('empresa/setar-modelo-documento', [EmpresaConfiguracaoController::class, 'setarModeloDocumento']);

                Route::get('/empresa/anulacacao/listarReciboRefCliente/{idCliente}', [OperacaoController::class, 'listarRecibosRecCliente']);

                Route::post('/empresa/documentoAnuladoFacturas/salvar', [OperacaoController::class, 'salvarDocumentoAnuladoFacturas']);
                Route::post('/empresa/documentoAnuladoRecibos/salvar', [OperacaoController::class, 'salvarDocumentoAnuladoRecibos']);
                Route::get('/empresa/imprimirDocumentoAnuladoFacturas/{facturaId}', [OperacaoController::class, 'imprimirDocumentoAnuladoFacturas']);
                Route::get('/empresa/imprimirDocumentoAnuladoRecibos/{reciboId}', [OperacaoController::class, 'imprimirDocumentoAnuladoRecibos']);

                //Inventarios
//            Route::get('/empresa/inventarios', [InventarioController::class, 'index'])->middleware('hasPermission:gerir inventario');

                Route::get('/empresa/inventarios', InventarioIndexController::class)->middleware('hasPermission:gerir inventario');
                Route::get('/empresa/inventario/novo', InventarioCreateController::class)->name('inventarioCreate')->middleware('hasPermission:gerir inventario');
                Route::post('/empresa/inventario/adicionar', [InventarioController::class, 'store']);
                Route::get('/empresa/inventario/imprimir/{inventarioId}', [InventarioController::class, 'imprimirInventario']);

                //documento retificados
                Route::post('/empresa/salvarFacturasRecitificadas', [FacturaController::class, 'salvarFacturasRecitificadas']);
                Route::get('/empresa/imprimirDocumentoRetificado/{docRetificadoId}/{idFactura}', [OperacaoController::class, 'imprimirDocumentoRetificado']);

                Route::get('/empresa/documentosRectificados/clientes', [OperacaoController::class, 'ListarClientesFacturasComFaturaRecibo']);
                Route::get('/empresa/listarFacturasAoSelecionarTipoDocumento/{idCliente}/{tipoDocumento}', [OperacaoController::class, 'ListarFacturasAoSelecionarDocumento']);

                //Emitir notas de credito e debito

                Route::get('/empresa/notaCreditoDebitoListarclientes', [OperacaoController::class, 'listarClientes']);

                Route::get('/empresa/imprimirNotaCredito/{idNotaCredito}', [OperacaoController::class, 'imprimirNotaCredito']);
                Route::get('/empresa/imprimirTodasNotaCredito', [OperacaoController::class, 'imprimirTodasNotaCredito']);

                Route::post('/empresa/notaCreditoSalvar', [OperacaoController::class, 'salvarNotaCredito']);
                Route::post('/empresa/notaDebitoSalvar', [OperacaoController::class, 'salvarNotaDebito']);
                Route::get('/empresa/imprimirNotaDebito/{idNotaDebito}', [OperacaoController::class, 'imprimirNotaDebito']);
                Route::get('/empresa/imprimirTodasNotaDebito', [OperacaoController::class, 'imprimirTodasNotaDebito']);
                Route::get('/empresa/listarNotaCredito', [OperacaoController::class, 'listarNotaCredito']);

                //upload img empresa
                Route::post('/empresa/update_logomarca/{id}', [EmpresaController::class, 'alterarLogotipo']);
            });
        });

        // Route::get('/baixar-manual',[BaixarManualController::class,'imprimirManualUtilizador']);
        Route::get('/empresa/infoDashboard', [AuthController::class, 'infoDashboard']);
        Route::post('/empresa/update_senha/{id}', [UserController::class, 'alterarPassword']);
        Route::get('empresa/perfil', [AuthController::class, 'perfil']);

    });
    Route::middleware(['ControlProduto', 'auth:empresa', 'ModulosAcesso'])->group(function () {
        Route::get('empresa/home', [EmpresaHomeController::class, 'index'])->name('funcionario/home');
    });

});



//Route::get('/empresa/{vue}', 'SpaController@index')->where('vue', '.*');
