@php use Illuminate\Support\Str; @endphp
@section('title','Pagamentos vendas online')
<div>
    <div class="row">
        <div class="modal fade" id="modalAtivarPagamento" wire:ignore>
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            VALIDAR PAGAMENTO VENDAS ONLINE
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="left: 0%; position: relative;">

                            <div class="col-md-12">
                                <form class="filter-form form-horizontal validation-form">
                                    <div class="second-row">
                                        <div class="tabbable">
                                            <div class="tab-content profile-edit-tab-content">
                                                <div id="dados_motivo" class="tab-pane in active">
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <h5>Deseja validar este pagamento?</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="validarPagamento">

                                                    <span wire:loading.remove wire:target="validarPagamento">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        VALIDAR O PAGAMENTO
                                                    </span>
                                                        <span wire:loading wire:target="validarPagamento">
                                                        <span class="loading"></span>
                                                        Aguarde...</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalRejeitarPagamento" wire:ignore>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            REJEITAR PAGAMENTO VENDAS ONLINE
                        </h4>

                    </div>
                    <div class="modal-body">
                        <div class="row" style="left: 0%; position: relative;">

                            <div class="col-md-12">
                                <form class="filter-form form-horizontal validation-form">
                                    @if ($errors->has('motivoRejeicao'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('motivoRejeicao') }}</strong>
                                    </span>
                                    @endif
                                    <div class="second-row">
                                        <div class="tabbable">
                                            <div class="tab-content profile-edit-tab-content">
                                                <div id="dados_motivo" class="tab-pane in active">
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2" for="cliente">Informe o motivo<b class="red fa fa-question-circle"></b></label>
                                                            <div>
                                                                <textarea wire:model="motivoRejeicao" class="col-md-12 col-xs-12 col-sm-4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="rejeitaPagamento">

                                                    <span wire:loading.remove wire:target="rejeitaPagamento">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        REJEITAR
                                                    </span>
                                                        <span wire:loading wire:target="rejeitaPagamento">
                                                        <span class="loading"></span>
                                                        Aguarde...</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if($detalhe)

        <div class="modal fade" id="modalDetalhesPagamento" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            DETALHES DO PAGAMENTO
                        </h4>

                    </div>

                        <div class="modal-body">
                            <div class="row" style="left: 0%; position: relative;">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th colspan="3"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>NOME DO CLIENTE</td>
                                            <td colspan="3">{{ $detalhe['nomeUserEntrega'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>ENDEREÇO</td>
                                            <td colspan="3">{{ $detalhe['enderecoEntrega'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>CONTACTO</td>
                                            <td colspan="3">{{ $detalhe['telefoneUserEntrega'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>PONTO DE REFÊRENCIA</td>
                                            <td colspan="3">{{ isset($detalhe['pontoReferenciaEntrega'])?$detalhe['pontoReferenciaEntrega']:"" }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" style="text-align: center">DADOS DO PAGAMENTO</th>
                                        </tr>
                                        <tr>
                                            <td>CÓDIGO DE PAGAMENTO</td>
                                            <td colspan="3">{{ $detalhe['codigo'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>BANCO</td>
                                            <td colspan="3">{{ $detalhe['nomeBanco'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>TIPO DE ENTREGA</td>
                                            <td colspan="3">{{ $detalhe['tipoEntrega'] }}</td>
                                        </tr>

                                        <tr>
                                            <td>TAXA DE ENTREGA</td>
                                            <td colspan="3">{{ number_format($detalhe['taxaEntrega'], 2, ',', '.') }}</td>
                                        </tr>

                                        <tr>
                                            <td>TOTAL IVA</td>
                                            <td colspan="3">{{ number_format($detalhe['totalIva'], 2, ',', '.') }}</td>
                                        </tr>

                                        <tr>
                                            <td><b>TOTAL PAGAMENTO</b></td>
                                            <td colspan="3"><b>{{ number_format($detalhe['totalPagamento'], 2, ',', '.') }}</b></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @endif
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                PAGAMENTOS VENDAS ONLINE
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Listagem
                </small>
            </h1>
        </div>

        <div class="col-md-12">
            <div class>
                <div class="row">
                    <form id="adimitirCandidatos" method="POST" action>
                        <input type="hidden" name="_token" value/>

                        <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                            <div class="clearfix" style="display: flex;padding: 5px 5px; align-items: center">
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <input type="date" wire:model="filter.dataInicial" class="col-md-12" style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <input type="date" wire:model="filter.dataFinal" class="col-md-12" style="line-height: 22px"/>
                                </div>

                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <select wire:model="filter.status" data="status" class="col-md-12 select2">
                                        <option value="">Status</option>
                                        <option value="1" <?= $filter['status'] == 1 ?'selected':null?>>VÁLIDADO</option>
                                        <option value="2" <?= $filter['status'] == 2?'selected':null?>>PENDENTE</option>
                                        <option value="3" <?= $filter['status'] == 3?'selected':null?>>REJEITADO</option>
                                        <option value="4" <?= $filter['status'] == 4?'selected':null?>>EM PROCESSO</option>
                                        <option value="5" <?= $filter['status'] == 5?'selected':null?>>ENTREGUE</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <select wire:model="filter.tipoEntregaId" data="tipoEntregaId" class="col-md-12 select2">
                                        <option value="">Filtro tipo de entrega</option>
                                        <option value="1" <?= $filter['tipoEntregaId'] == 1?'selected':null?>>EM CASA</option>
                                        <option value="2" <?= $filter['tipoEntregaId'] == 2?'selected':null?>>NA LOJA</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <select wire:model="filter.orderBy" data="orderBy" class="col-md-12 select2">
                                        <option value="DESC" <?= $filter['orderBy'] == 'DESC'?'selected':null?>>DESC</option>
                                        <option value="ASC" <?= $filter['orderBy'] == 'ASC'?'selected':null?>>ASC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-header widget-header">
                                Todos pagamentos do sistema
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Cliente</th>
                                        <th>Endereço entrega</th>
                                        <th>Telefone</th>
                                        <th>Banco</th>
                                        <th style="text-align:right">Total pagamento</th>
                                        <th style="text-align:right">Taxa entrega</th>
                                        <th style="text-align:right">Entrega</th>
                                        <th style="text-align:center">Data pagamento</th>
                                        <th style="text-align: center">Status</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pagamentos as $pagamento)
                                        <tr>
                                            <td>{{ $pagamento->codigo }}</td>
                                            <td>{{ Str::title($pagamento->nomeUser) }}</td>
                                            <td>{{ $pagamento->comuna?$pagamento->comuna->municipio->designacao.', '.$pagamento->comuna->designacao.', ':null }} {{ Str::title($pagamento->enderecoEntrega) }}</td>
                                            <td>{{ Str::title($pagamento->telefoneUserEntrega) }}</td>
                                            <td>{{$pagamento->banco? Str::title($pagamento->banco->designacao):null }}</td>
                                            <td style="text-align: right">{{ number_format($pagamento->totalPagamento, 2, ',', '.') }}</td>
                                            <td style="text-align: right">{{ number_format($pagamento->taxaEntrega, 2, ',', '.') }}</td>
                                            <td style="text-align: right">{{$pagamento->tipoEntregaId== 1?'Em Casa':'Na LOja' }}</td>
                                            <td style="text-align:center">{{ date("d/m/Y H:m", strtotime($pagamento->created_at)) }}</td>
                                            <td style="text-align: center">
                                                <span
                                                    class="label label-sm <?= $pagamento->statu->id == 1 ? 'label-success' : ($pagamento->statu->id == 2?'label-warning':'label-danger') ?>"
                                                    style="border-radius: 20px;">{{ $pagamento->statu->designacao }}</span>
                                            <td>
                                                <a wire:click="visualizarDadosPagamento({{ json_encode($pagamento)}})" href="#modalDetalhesPagamento"

                                                   data-toggle="modal" class="pink"
                                                   title="Visualizar dados do pagamento">
                                                    <i class="ace-icon fa fa-eye bigger-150 bolder success pink"></i>
                                                </a>
                                                <a class="blue"
                                                   wire:click="imprimirPagamentoVendaOnline({{$pagamento->id}})"
                                                   title="Visualizar detalhes do pagamento" style="cursor: pointer">
                                                    <i class="ace-icon fa fa-print bigger-150 bolder success text-primary"></i>
                                                    <span wire:loading
                                                          wire:target="imprimirPagamentoVendaOnline({{$pagamento->id}})"
                                                          class="loading">
                                                    <i class="ace-icon fa fa-print bigger-150 bolder success text-primary"></i>
                                                </span>
                                                </a>

                                                @if($pagamento['statusPagamentoId'] == 3)
                                                    <a class="pink " href="#modalAtivarPagamento" data-toggle="modal" title="Válidar o pagamento" wire:click="modalAceitarPagamento({{ $pagamento->id }})" style="cursor: pointer;">
                                                        <i class="ace-icon fa fa-check bigger-150 bolder sucess text-success"></i>
                                                    </a>
                                                @endif

                                                @if($pagamento['statusPagamentoId'] == 2)
                                                <a class="pink" wire:click="modalRejeitarPagamento({{ $pagamento->id }})" href="#modalRejeitarPagamento" data-toggle="modal" title="Rejeitar o pagamento" style="cursor: pointer;">
                                                    <i class="ace-icon fa fa-remove bigger-150 bolder danger text-danger"></i>
                                                </a>
                                                <a class="pink" href="#modalAtivarPagamento" data-toggle="modal"  title="Válidar o pagamento" wire:click="modalAceitarPagamento({{ $pagamento->id }})" style="cursor: pointer;">
                                                    <i class="ace-icon fa fa-check bigger-150 bolder sucess text-success"></i>
                                                </a>
                                                @endif
                                                <a class="pink" title="visualizar o comprovativo bancário" href="{{ '/upload/'.$pagamento->comprovativoBancario }}" target="_blank" style="cursor: pointer;">
                                                    <i class="ace-icon fa fa-eye bigger-150 bolder green text-blue"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

