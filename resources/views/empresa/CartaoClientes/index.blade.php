@php use Carbon\Carbon; @endphp
@section('title','Cartão Cliente')
<div class="row">
    <div class="modal fade" id="modalUpdateCartaoCliente" wire:ignore>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller">
                        ATUALIZAR CARTÃO CLIENTE
                    </h4>

                </div>

                <div class="modal-body">
                    <div class="row" style="left: 0%; position: relative;">

                        <div class="col-md-12">
                            <form class="filter-form form-horizontal validation-form">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <span>{{ $errors->all()[0] }}</span>
                                    </div>
                                @endif
                                <div class="second-row">
                                    <div class="tabbable">
                                        <div class="tab-content profile-edit-tab-content">
                                            <div id="dados_motivo" class="tab-pane in active">
                                                <div class="form-group has-info">
                                                    <div class="col-md-12">
                                                        <label class="control-label bold label-select2" for="cliente">Cliente</label>
                                                        <div>
                                                            <input type="text" wire:model="cartaoCliente.nomeCliente"
                                                                   disabled id="cliente"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-info">
                                                    <div class="col-md-6">
                                                        <label class="control-label bold label-select2"
                                                               for="numeroCartao">Número Cartão</label>
                                                        <div>
                                                            <input type="text" wire:model="cartaoCliente.numeroCartao"
                                                                   disabled
                                                                   id="numeroCartao"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label bold label-select2" for="saldo">Saldo</label>
                                                        <div>
                                                            <input type="text" wire:model="cartaoCliente.saldo" disabled
                                                                   id="saldo"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group has-info">
                                                    <div class="col-md-6">
                                                        <label class="control-label bold label-select2"
                                                               for="dataEmissao">Data Emissão<b
                                                                class="red fa fa-question-circle"></b></label>
                                                        <div>
                                                            <input type="date" wire:model="cartaoCliente.dataEmissao" style="<?= $errors->has('cartaoCliente.dataEmissao') ? 'border-color: #ff9292;' : '' ?>" id="dataEmissao" class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                        @if ($errors->has('cartaoCliente.dataEmissao'))
                                                            <span class="help-block" style="color: red; font-weight: bold">
                                                                <strong>{{ $errors->first('cartaoCliente.dataEmissao') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="control-label bold label-select2"
                                                               for="dataValidade">Data Válidade<b
                                                                class="red fa fa-question-circle"></b></label>
                                                        <div>
                                                            <input type="date" wire:model="cartaoCliente.dataValidade"
                                                                   id="numeroCartao"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-9">
                                                <button class="search-btn" style="border-radius: 10px"
                                                        wire:click.prevent="atualizarCartaoCliente">

                                                    <span wire:loading.remove wire:target="atualizarCartaoCliente">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                    <span wire:loading wire:target="atualizarCartaoCliente">
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

    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            CARTÃO CLIENTES
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Listagem
            </small>
        </h1>
    </div>

    <div class="col-md-12">
        <div class>
            <form class="form-search" method="get" action>
                <div class="row">
                    <div class>
                        <div class="input-group input-group-sm" style="margin-bottom: 10px">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-search"></i>
                            </span>

                            <input type="text" wire:model="search" autofocus autocomplete="on"
                                   class="form-control search-query"
                                   placeholder="Buscar por nome do cliente, numero do cartão"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-lg upload">
                                    <span class="ace-icon fa fa-search icon-on-right bigger-130"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class>
            <div class="row">
                <form id="adimitirCandidatos" method="POST" action>
                    <input type="hidden" name="_token" value/>

                    <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                        <div class="clearfix">
                            <a href="{{ route('cartaoClienteCreate') }}" title="emitir novo cartão cliente"
                               class="btn btn-success widget-box widget-color-blue" id="botoes">
                                <i class="fa icofont-plus-circle"></i> Novo Cartão cliente
                            </a>

                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos cartão clientes do sistema (Total:{{count($cartaoClientes)}})
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Número Cartão</th>
                                    <th>Saldo</th>
                                    <th>Data Emissão</th>
                                    <th>Data Válidade</th>
                                    <th style="text-align: center">Status</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cartaoClientes as $cartaoCliente)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Str::upper($cartaoCliente->cliente->nome) }}</td>
                                        <td>{{ $cartaoCliente->numeroCartao }}</td>
                                        <td>{{ number_format($cartaoCliente->saldo, 2, ',', '.') }}</td>
                                        <td>{{ date("d/m/Y", strtotime($cartaoCliente->dataEmissao)) }}</td>
                                        <td>{{ date("d/m/Y", strtotime($cartaoCliente->dataValidade)) }}</td>
                                        <td style="text-align: center">
                                            <span
                                                class="label label-sm <?= (Carbon::now() > $cartaoCliente->dataValidade) ? 'label-danger' : 'label-success' ?>"
                                                style="border-radius: 20px;">
                                                {{ (Carbon::now() > $cartaoCliente->dataValidade)? 'Expirado':'Válido' }}</span>
                                        </td>
                                        <td>
                                            <a href="#modalUpdateCartaoCliente" data-toggle="modal"
                                               wire:click="showModalUpdateCartaoCliente({{ json_encode($cartaoCliente) }})"
                                                class="pink"
                                               title="Botão para inicializar a sequência do documento">
                                                <i class="fa fa-pencil-square-o bigger-200 blue"></i>
                                            </a>

                                            <a class="blue" wire:click="baixarCartaoCliente({{$cartaoCliente->id}})"
                                               title="baixar o cartão cliente" style="cursor: pointer">
                                                <i class="fa fa-download bigger-200 orange"></i>
                                                <span wire:loading
                                                      wire:target="baixarCartaoCliente({{$cartaoCliente->id}})"
                                                      class="loading">
                                                    <i class="fa fa-download bigger-200 orange"></i>
                                                </span>
                                            </a>
                                            <a class="blue" wire:click="baixarHistoricoCartaoCliente({{$cartaoCliente->clienteId}})"
                                               title="baixar o extrato do cartão cliente" style="cursor: pointer">
                                                <i class="fa fa-credit-card bigger-200 primary"></i>
                                                <span wire:loading
                                                      wire:target="baixarHistoricoCartaoCliente({{$cartaoCliente->clienteId}})"
                                                      class="loading">
                                                    <i class="fa fa-credit-card bigger-200 primary"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                {{ $cartaoClientes->links() }}
            </div>

        </div>

    </div>
</div>
