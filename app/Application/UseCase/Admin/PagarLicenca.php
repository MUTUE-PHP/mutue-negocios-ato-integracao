<?php

namespace App\Application\UseCase\Admin;

use App\Domain\Entity\Admin\AtivacaoLicenca;
use App\Domain\Entity\Admin\Empresa;
use App\Domain\Entity\Admin\FaturaLicenca;
use App\Domain\Entity\Admin\FormaPagamento;
use App\Domain\Entity\Admin\Licenca;
use App\Domain\Entity\Admin\PagamentoLicenca;
use App\Domain\Factory\Admin\RepositoryFactory;
use App\Domain\Interfaces\Admin\IGerarReciboPagamentoLicenca;
use App\Domain\Interfaces\IHashDocumentoRepository;
use App\Http\Controllers\admin\ReportShowAdminController;
use App\Infra\Repository\Admin\AtivacaoLicencaRepository;
use App\Infra\Repository\Admin\ClienteRepository;
use App\Infra\Repository\Admin\FaturaRepository;
use App\Infra\Repository\Admin\FormaPagamentoRepository;
use App\Infra\Repository\Admin\LicencaRepository;
use App\Infra\Repository\Admin\PagamentoLicencaRepository;
use App\Infra\Repository\Admin\UserRepository;
use App\Mail\NotificacaoAdminsAtivacaoLicenca;
use App\Traits\Admin\TraitSerieDocumentoAdmin;
use App\Traits\TraitHashDocumento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PagarLicenca
{
    use TraitSerieDocumentoAdmin;
    use TraitHashDocumento;

    private PagamentoLicencaRepository $pagamentoLicencaRepository;
    private FaturaRepository $faturaRepository;
    private ClienteRepository $clienteRepository;
    private LicencaRepository $licencaRepository;
    private FormaPagamentoRepository $formaPagamentoRepository;
    private UserRepository $userRepository;
    private $relatorioReciboPagamentoLicenca;
    private AtivacaoLicencaRepository $ativacaoLicencaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->pagamentoLicencaRepository = $repositoryFactory->createPagamentoLicencaRepository();
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
        $this->licencaRepository = $repositoryFactory->createLicencaRepository();
        $this->formaPagamentoRepository = $repositoryFactory->createFormaPagamentoRepository();
        $this->userRepository = $repositoryFactory->createUserRepository();
        $this->ativacaoLicencaRepository = $repositoryFactory->createAtivacaoLicencaRepository();
    }

    public function execute($request)
    {
        $licenca = $this->licencaRepository->getLicenca($request->licencaId);
        if (!$licenca) throw new \Exception("Licença não encontrado");
        $empresa = $this->clienteRepository->getCliente($request->empresaId);
        if (!$empresa) throw new \Exception("Cliente não encontrado");
        $formaPagamento = $this->formaPagamentoRepository->getFormaPagamento($request->formaPagamentoId);
        if (!$formaPagamento) throw new \Exception("Forma de pagamento não encontrado");
        $numeroSequenciaFatura = $this->faturaRepository->getNumeroSequenciaFatura();
        $numeracaoFactura = 'FT ' . $this->mostrarSerieDocumento() . date('Y') . '/' . $numeroSequenciaFatura;
        $ultimaFatura = $this->faturaRepository->getUltimaFatura();
        $quantidade = $request->quantidade;

        if (!$ultimaFatura) {
            $hashAnteriorFaturaLicenca = null;
        } else {
            $hashAnteriorFaturaLicenca = $ultimaFatura->hashValor;
        }
        $dataAtual = Carbon::now();
        $hashValorFaturaLicenca = $this->gerarHash($numeracaoFactura, $hashAnteriorFaturaLicenca, $licenca->valor, $dataAtual);
        $fatura = new FaturaLicenca(
            new Licenca($licenca->id, $licenca->designacao, $licenca->valor, $licenca->limite_usuario, $licenca->taxaIva->taxa),
            new Empresa($empresa->id, $empresa->nome, $empresa->endereco, $empresa->nif, $empresa->email, $empresa->pessoal_Contacto),
            new FormaPagamento($formaPagamento->id, $formaPagamento->descricao),
            $numeroSequenciaFatura,
            $numeracaoFactura,
            $hashValorFaturaLicenca,
            $request->observacao,
            $quantidade
        );

        $faturaDatabase = $this->faturaRepository->emitirFatura($fatura);
        //RECIBOS PAGAMENTO
        $numeroSequenciaRecibo = $this->pagamentoLicencaRepository->getNumeroSequenciaRecibo();
        $numeracaoRecibo = 'RC ' . $this->mostrarSerieDocumento() . date('Y') . '/' . $numeroSequenciaRecibo;
        $ultimoRecibo = $this->pagamentoLicencaRepository->getUltimoRecibo();
        if (!$ultimoRecibo) {
            $hashAnteriorPagamento = null;
        } else {
            $hashAnteriorPagamento = $ultimoRecibo->hash;
        }
        $dataAtual = Carbon::now();
        $hashValorRecibo = $this->gerarHash($numeracaoRecibo, $hashAnteriorPagamento, $licenca->valor, $dataAtual);
        $hashTexto = $this->getTextHash();
        $comprovativoBancario = $request['comprovativoBancario'] ? $request['comprovativoBancario']->store("/admin/licenca") : null;
        $dataValidacao = Carbon::now();
        $statusId = 1;
        $observacao = "Liquidação da fatura " . $fatura->getNumeracaoFatura();
        $operacaoBancaria = $this->pagamentoLicencaRepository->verificarExistenciaNumeroOperacaoBancaria($request->numeroOperacaoBancaria);
        if ($operacaoBancaria) throw new \Exception("O número de operação bancária já cadastrado");
        $pagamento = new PagamentoLicenca(
            $empresa->id,
            $faturaDatabase->id,
            $fatura->getTotalPrecoFatura(),
            $fatura->getTotalPrecoFatura(),
            $quantidade,
            $request->dataPagamentoBanco,
            $request->numeroOperacaoBancaria,
            $numeracaoRecibo,
            $hashValorRecibo,
            $hashTexto,
            $fatura->getTotalPorExtenso(),
            $numeroSequenciaRecibo,
            $request->formaPagamentoId,
            $request->contaMovimentadaId,
            $comprovativoBancario,
            $observacao,
            $statusId,
            $dataValidacao,
            $fatura->getNumeracaoFatura()
        );

        $MENSAL = 2;
        $ANUAL = 3;
        $DEFINITIVA = 4;
        $STATUS_ATIVO = 1;

        if ($licenca->tipo_licenca_id === $MENSAL) {
            $day = 31 * $quantidade;
            $data_final = Carbon::now()->addDay($day);
        } else if ($licenca->tipo_licenca_id === $ANUAL) {
            $day = 365 * $quantidade;
            $data_final = Carbon::now()->addDay($day);
        } else {
            $request['quantidade'] = 1;
            $data_final = null;
        }
        $ultimaLicenca = $this->ativacaoLicencaRepository->getUltimaAtivacaoLicenca($request->empresaId);
        $data_inicio = Carbon::parse($ultimaLicenca->data_fim)->addDay(1);
        $pagamento = $this->pagamentoLicencaRepository->fazerPagamentoFatura($pagamento);
        $ativacaoLicenca = new AtivacaoLicenca(
            $licenca->id,
            $empresa->id,
            $pagamento->id,
            $data_inicio,
            $data_final,
            Carbon::now(),
            auth()->user()->id,
            auth()->user()->name,
            $STATUS_ATIVO,
            $request->observacao,
        );
        $this->ativacaoLicencaRepository->ativarLicenca($ativacaoLicenca);

        $dataAdmin['emails'] = $this->userRepository->buscarAdminParaNotificacaoAtivacaoLicenca();
        array_push($dataAdmin['emails'], $empresa->email);
        $pathRecibo = $this->imprimirReciboPagamento($pagamento->id, $empresa->referencia);
        $pathFatura = $this->imprimirFatura($faturaDatabase->id, $empresa->referencia);
        $dataAdmin['reciboPagamento'] = $pathRecibo['filename'];
        $dataAdmin['fatura'] = $pathFatura['filename'];
        $dataAdmin['linkLogin'] = getenv('APP_URL');
        $dataAdmin['nomeEmpresa'] = $empresa->nome;
        $dataAdmin['data_final'] = $data_final;
        $dataAdmin['data_inicio'] = $data_inicio;
        $dataAdmin['tipoLicenca'] = $licenca->designacao;
        $dataAdmin['nomeOperador'] = auth()->user()->name;
         try {
             Mail::send(new NotificacaoAdminsAtivacaoLicenca($dataAdmin));
         } catch (\Exception $e) {
             Log::error($e->getMessage());
         }
        return $pagamento;
    }

    private function imprimirFatura($facturaId, $referenciaEmpresa)
    {
        $filename = 'facturaA4Admin';
        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $empresaCliente = DB::connection('mysql')->table('empresas')->where('referencia', $referenciaEmpresa)->first();
        $logotipo = public_path() . '/upload//' . $empresa->logotipo;
        $DIR = public_path() . "/upload/documentos/admin/relatorios/";


        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 1,
                    'facturaId' => $facturaId,
                    'logotipo' => $logotipo,
                    'empresa_id' => $empresaCliente->id,
                    'EmpresaNome' => $empresa->nome,
                    'EmpresaEndereco' => $empresa->endereco,
                    'EmpresaNif' => $empresa->nif,
                    'EmpresaTelefone' => $empresa->pessoal_Contacto,
                    'EmpresaEmail' => $empresa->email,
                    'EmpresaWebsite' => $empresa->website,
                    'operador' => auth()->user()->name,
                    'DIR' => $DIR
                ]
            ]
        );
        return $report;
    }

    private function imprimirReciboPagamento($pagamentoId, $referenciaEmpresa)
    {
        $filename = 'reciboPagamentoPedente';
        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $empresaCliente = DB::connection('mysql')->table('empresas')->where('referencia', $referenciaEmpresa)->first();
        $logotipo = public_path() . '/upload//' . $empresa->logotipo;

        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 1,
                    'pagamentoId' => $pagamentoId,
                    'logotipo' => $logotipo,
                    'empresa_id' => $empresaCliente->id,
                    'EmpresaNome' => $empresa->nome,
                    'EmpresaEndereco' => $empresa->endereco,
                    'EmpresaNif' => $empresa->nif,
                    'EmpresaTelefone' => $empresa->pessoal_Contacto,
                    'EmpresaEmail' => $empresa->email,
                    'EmpresaWebsite' => $empresa->website,
                    'operador' => auth()->user()->name
                ]
            ]
        );
        return $report;
    }
}
