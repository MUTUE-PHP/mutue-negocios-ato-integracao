<?php

namespace App\Http\Controllers\VM\Pagamentos;

use App\Application\UseCase\VendasOnline\PagamentoCompras\AtualizarHistoricoPagamentoOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\GetPagamentoVendasOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ListarTodosPagamentosVendasOnline;
use App\Application\UseCase\VendasOnline\ValidacaoPagamento\PagamentoRejeitado;
use App\Application\UseCase\VendasOnline\ValidacaoPagamento\PagamentoValidado;
use App\Domain\Service\FirebaseNotification\INotificationFirebase;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\Relatorios\RelatorioPagamentoVendaOnlineJasper;
use App\Infra\Service\NotificationFirebase;
use App\Mail\NotificacaoPagamentoVendaOnline;
use App\Repositories\Empresa\FacturaRepository;
use App\Repositories\Empresa\ParametroRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class PagamentoVendaOnlineIndexController extends Component
{
    use RelatorioPagamentoVendaOnlineJasper;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    use LivewireAlert;

    private $facturaRepository;
    private INotificationFirebase $notificationMobileFirebase;


    public $errors = [];
    public $loading = false;
    public $search = null;
    public $pagamento;
    public $detalhe;
    public $motivoRejeicao = null;
    public $pagamentoId;
    public $filter = [
        'search' => null,
        'tipoEntregaId' => null,
        'dataInicial' => null,
        'dataFinal' => null,
        'status' => null,
        'orderBy' => 'DESC'
    ];
    public $modalPagamento = null;

    protected $listeners = [
        'selectedItem', 'validarPagamento'
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->resetPage();
        $this->filter[$item['atributo']] = $item['valor'];
    }
    public function boot(FacturaRepository $facturaRepository, NotificationFirebase $notificationFirebase)
    {
        $this->pagamento['banco']['designacao'] = null;
        $this->setarDetalhe();
        $this->facturaRepository = $facturaRepository;
        $this->notificationMobileFirebase = $notificationFirebase;
    }

    public function setarDetalhe()
    {
        $this->filter['dataInicial'] = Carbon::now()->addDay(-31)->format('Y-m-d');
        $this->filter['dataFinal'] = Carbon::now()->format('Y-m-d');
        $this->filter['status'] = 2;
        $this->detalhe['nomeUserEntrega'] = null;
        $this->detalhe['enderecoEntrega'] = null;
        $this->detalhe['telefoneUserEntrega'] = null;
        $this->detalhe['pontoReferenciaEntrega'] = null;
        $this->detalhe['codigo'] = null;
        $this->detalhe['totalPagamento'] = null;
        $this->detalhe['tipoEntrega'] = null;
        $this->detalhe['taxaEntrega'] = null;
        $this->detalhe['nomeBanco'] = null;
        $this->detalhe['totalIva'] = null;
    }

    public function render($loading = false)
    {
        $this->loading = $loading;
        $getPagamentosVendasOnline = new ListarTodosPagamentosVendasOnline(new DatabaseRepositoryFactory());
        $data['pagamentos'] = $getPagamentosVendasOnline->execute($this->filter);
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.pagamentosVendasOnline.index', $data);
    }

    public function visualizarDadosPagamento($pagamento)
    {
        $this->detalhe['nomeUserEntrega'] = $pagamento['nomeUserEntrega'];
        $this->detalhe['enderecoEntrega'] = $pagamento['enderecoEntrega'];
        $this->detalhe['telefoneUserEntrega'] = $pagamento['telefoneUserEntrega'];
        $this->detalhe['pontoReferenciaEntrega'] = $pagamento['pontoReferenciaEntrega'];
        $this->detalhe['codigo'] = $pagamento['codigo'];
        $this->detalhe['totalPagamento'] = $pagamento['totalPagamento'];
        $this->detalhe['taxaEntrega'] = $pagamento['taxaEntrega'];
        $this->detalhe['totalIva'] = $pagamento['totalIva'];
        $this->detalhe['tipoEntrega'] = $pagamento['tipo_entrega']['designacao'];
        $this->detalhe['nomeBanco'] = $pagamento['banco']['designacao'];
    }


    public function modalAceitarPagamento($pagamentoId)
    {
        $this->pagamentoId = $pagamentoId;
//        $this->confirm('Deseja válidar o pagamento?', [
//            'onConfirmed' => 'validarPagamento',
//            'cancelButtonText' => 'Não',
//            'confirmButtonText' => 'Sim',
//        ]);

    }

    public function rejeitaPagamento()
    {
        $rules = [
            'motivoRejeicao' => ["required"]
        ];
        $messages = [
            'motivoRejeicao.required' => 'Informe o motivo da rejeição',
        ];

        $this->validate($rules, $messages);
        $getPagamento = new GetPagamentoVendasOnline(new DatabaseRepositoryFactory());
        $pagamento = $getPagamento->execute($this->pagamentoId);

        $pagamento['motivoRejeicao'] = $this->motivoRejeicao;
        $pagamentoRejeitado = new PagamentoRejeitado(new DatabaseRepositoryFactory());
        $pagamento = $pagamentoRejeitado->execute($pagamento);
        if ($pagamento) {
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->motivoRejeicao = null;
        }

    }

    public function modalRejeitarPagamento($pagamentoId)
    {
        $this->pagamentoId = $pagamentoId;
    }

    public function validarPagamento()
    {
        $getPagamento = new GetPagamentoVendasOnline(new DatabaseRepositoryFactory());
        $pagamento = $getPagamento->execute($this->pagamentoId);

        $validarPagamento = new PagamentoValidado(new DatabaseRepositoryFactory());
        $faturaId = $validarPagamento->execute($pagamento);
        $PAGAMENTO_VALIDADO = 1;
        $descricao = "Pagamento válido pelo operador ".Str::title(auth()->user()->name);
        $atualizarHistoricoPagamento = new AtualizarHistoricoPagamentoOnline(new DatabaseRepositoryFactory());
        $atualizarHistoricoPagamento->execute($pagamento->id, $PAGAMENTO_VALIDADO, $descricao);

        if ($faturaId) {
            if($pagamento->user->token_notification_firebase){
                $to = $pagamento->user->token_notification_firebase;
                $title = 'Pagamento Validado';
                $body = "Seu pagamento nº $this->pagamentoId foi validado com sucesso. Obrigado por sua compra!";
                $type = 'msj';
                $id = $pagamento->id;
                $route = '';
                $this->notificationMobileFirebase->notify($to, $title, $body, $type, $id, $route);
            }
            $this->imprimirFactura($faturaId);
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        }
//        $this->dispatchBrowserEvent('loading');


    }
    public function imprimirPagamentoVendaOnline($pagamentoId){
        $this->imprimirPagamento($pagamentoId);
    }
}
