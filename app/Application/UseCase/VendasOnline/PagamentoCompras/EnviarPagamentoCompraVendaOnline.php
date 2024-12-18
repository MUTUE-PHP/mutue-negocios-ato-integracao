<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\VendasOnline\Clientes\GetClientePeloUserId;
use App\Application\UseCase\VendasOnline\Estoque\AtualizarExistenciaStock;
use App\Domain\Entity\VendasOnline\EnderecoEntregaVendasOnline;
use App\Domain\Entity\VendasOnline\PagamentoItemVendaOnline;
use App\Domain\Entity\VendasOnline\PagamentoVendasOnline;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\CarrinhoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\ComunasFreteRepository;
use App\Infra\Repository\VendasOnline\MunicipiosFreteRepository;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\RelatorioVendaOnlineJasper;
use App\Infra\Repository\VendasOnline\UserRepository;
use App\Mail\NotificacaoPagamentoVendaOnline;
use App\Mail\NotificacaoPagamentoVendaOnlineAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarPagamentoCompraVendaOnline
{
    private PagamentoVendasOnlineRepository $pagamentoVendasOnlineRepository;
    private CarrinhoVendasOnlineRepository $carrinhoVendasOnlineRepository;
    private RelatorioVendaOnlineJasper $relatorioVendaOnlineJasper;
    private UserRepository $userAdminRepository;
    private ComunasFreteRepository $comunasFreteRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->carrinhoVendasOnlineRepository = $repositoryFactory->createCarrinhoVendaOnlineRepository();
        $this->pagamentoVendasOnlineRepository = $repositoryFactory->createPagamentoVendaOnlineRepository();
        $this->relatorioVendaOnlineJasper = $repositoryFactory->createRelatorioVendaOnlineJasper();
        $this->userAdminRepository = $repositoryFactory->createUserRepository();
        $this->comunasFreteRepository = $repositoryFactory->createComunasFreteRepository();
    }

    public function execute($request)
    {

        try {
            $userId = Auth::user()->id;
            $statusPendente = 2;
            $carrinhos = $this->carrinhoVendasOnlineRepository->getCarrinhos($userId);

            if (count($carrinhos) <= 0) throw new \Exception("Não existe produto no carrinho");
            $comprovativoBancario = $this->getNomeComprovativoBancario($request);

            $sequencia = $this->pagamentoVendasOnlineRepository->getSequenciaPagamento();
            $sequencia = str_pad($sequencia, 5, '0', STR_PAD_LEFT);

            $getEstimativaEntrega = new GetParametroPeloLabelNoParametro(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
            $estimativaEntrega = $getEstimativaEntrega->execute('estimativa_entrega');
            $estimativaEntrega =  $estimativaEntrega->valor;

            $instanciaEntrega = new TipoEntregaFatory(
                $request['tipoEntregaId'],
                $request['comunaId'],
                $this->comunasFreteRepository
            );

            $pagamento = new PagamentoVendasOnline(
                isset($request['bancoId'])?$request['bancoId']:null,
                isset($request['dataPagamentoBanco'])?$request['dataPagamentoBanco']:null,
                $comprovativoBancario,
                isset($request['formaPagamentoId'])?$request['formaPagamentoId']:null,
                $userId,
                $statusPendente,
                $instanciaEntrega,
                $estimativaEntrega,
                $request['cobrarTaxaEntrega'],
                $request['numeroCartaoCliente'],
                new EnderecoEntregaVendasOnline(
                    $request['nomeUserEntrega'],
                    $request['apelidoUserEntrega'],
                    $request['enderecoEntrega'],
                    $request['pontoReferenciaEntrega'],
                    $request['telefoneUserEntrega'],
                    $request['provinciaIdEntrega'],
                    $request['comunaId'],
                    $request['emailEntrega'],
                    $request['observacaoEntrega'],
                ),
                $sequencia
            );

            $getArmazem = new GetArmazens(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
            $armazens = $getArmazem->execute();
            $armazemId = $armazens[0]['id'];

            foreach ($carrinhos as $pagamentoItem) {
                $produtoId = $pagamentoItem->produto_id;
                $quantidade = $pagamentoItem->quantidade;
                $operacao = "-";
                $atualizarStock = new AtualizarExistenciaStock(new DatabaseRepositoryFactory());
                $atualizarStock->execute($produtoId, $quantidade, $armazemId, $operacao);
                $pagamentoItem = new PagamentoItemVendaOnline(
                    $pagamentoItem->produto_id,
                    $pagamentoItem->produto->designacao,
                    $pagamentoItem->produto->preco_venda,
                    $pagamentoItem->produto->tipoTaxa->taxa,
                    $pagamentoItem->quantidade
                );
                $pagamento->addItem($pagamentoItem);
            }

            $outputPagamento = $this->pagamentoVendasOnlineRepository->salvar($pagamento);
            if (!$outputPagamento) throw new \Exception("Erro ao fazer pagamento");

            foreach ($pagamento->getItems() as $item) {
                $this->pagamentoVendasOnlineRepository->salvarItemPagamento($item, $outputPagamento->id);
            }
            $this->carrinhoVendasOnlineRepository->limparCarrinhoApartirUser($userId);
            $pagamentoJasper = $this->relatorioVendaOnlineJasper->imprimirPagamentoVendaOnline($outputPagamento->id);
            $cliente = new GetClientePeloUserId(new DatabaseRepositoryFactory());
            $cliente = $cliente->execute($userId);
            $data['nomeEmpresa'] = $request['nomeUserEntrega'] ?? auth()->user->name;
            $data['nifEmpresa'] = $cliente->nif;
            $data['enderecoEmpresa'] = $request['enderecoEntrega'] ?? $cliente->endereco;
            $data['emails'] = $this->userAdminRepository->getEmailsAdminParaNotificar();
            $data['codigo'] = $outputPagamento->codigo;
            array_push($data['emails'], $request['emailEntrega'] ?? $cliente->email);
            $data['assunto'] = 'Confirmação de Pedido: ' . $outputPagamento->codigo;
            $data['pagamentoPDF'] = $pagamentoJasper['filename'];
            $data['viewNotificacao'] = $request['tipoEntregaId'] == 1 ? "pagamentosVendaOnlineEmCasaAdmin" : "pagamentosVendaOnlineNaLojaAdmin";
            Mail::send(new NotificacaoPagamentoVendaOnline($data));
            return $outputPagamento;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    private function getNomeComprovativoBancario($request){

        if(!$request['comprovativoBancario']) return null;
        $comprovativoBancario = $request['comprovativoBancario']->store('comprovativosVendasOnline');

        return $comprovativoBancario;
    }
}
