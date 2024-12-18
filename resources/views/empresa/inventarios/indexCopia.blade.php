@php use Carbon\Carbon;use Illuminate\Support\Str; @endphp
@section('title','Listar inventários')
<div class="row">
    <div class="modal fade" id="modalEmitirInventario" wire:ignore>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller">
                        NOVO INVENTÁRIO
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
                                                    <div class="col-md-8">
                                                        <label class="control-label bold label-select2" for="cliente">Centro
                                                            de custo</label>
                                                        <select class="col-md-12 select2" wire:model="centroCustoId" disabled
                                                                data="centroCustoId"
                                                                style="height:35px;<?= $errors->has('centroCustoId') ? 'border-color: #ff9292;' : '' ?>">
                                                            @foreach($centrosCusto as $centroCusto)
                                                                <option
                                                                    value="{{ $centroCusto->id }}" {{ $centroCustoCheck == $centroCusto->id?'selectd ':'' }}>{{ Str::upper($centroCusto->nome) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label bold label-select2" for="cliente">Armazém</label>
                                                        <select class="col-md-12 select2" wire:model="centroCustoId"
                                                                data="centroCustoId"
                                                                style="height:35px;<?= $errors->has('centroCustoId') ? 'border-color: #ff9292;' : '' ?>">
                                                            @foreach($armazens as $armazem)
                                                                <option
                                                                    value="{{ $armazem->id }}">{{ Str::upper($armazem->designacao) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group has-info">
                                                    <div class="col-md-8">
                                                        <label class="control-label bold label-select2"
                                                               for="dataEmissao">Data Emissão<b
                                                                class="red fa fa-question-circle"></b></label>
                                                        <div>
                                                            <input type="date" wire:model="cartaoCliente.dataEmissao"
                                                                   style="<?= $errors->has('cartaoCliente.dataEmissao') ? 'border-color: #ff9292;' : '' ?>"
                                                                   id="dataEmissao"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                        @if ($errors->has('cartaoCliente.dataEmissao'))
                                                            <span class="help-block"
                                                                  style="color: red; font-weight: bold">
                                                                <strong>{{ $errors->first('cartaoCliente.dataEmissao') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-info">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">Produto</th>
                                                                <th scope="col">Exist. Atual</th>
                                                                <th scope="col">Exist. Nova</th>
                                                                <th scope="col" style="text-align: right">Diferença</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($existenciaStock as $existencia)
                                                            <tr>
                                                                <td>{{ Str::title($existencia->produto->designacao) }}</td>
                                                                <td style="width: 100px;">{{ number_format($existencia->quantidade, 1, ',','.') }}</td>
                                                                <td>
                                                                    <input type="number" wire:input="atualizarQuantidade({{ $existencia }})" style="width: 100px">
                                                                </td>
                                                                <td style="text-align: right">{{ number_format(1, 1,',', '.') }}</td>
                                                            </tr>
                                                            @endforeach

                                                            </tbody>
                                                        </table>
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
            INVENTÁRIOS
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
                                   placeholder="Buscar pela númeração do inventário"/>
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
                            <a href="{{ route('inventarioCreate') }}" title="emitir novo cartão cliente"
                               class="btn btn-success widget-box widget-color-blue" id="botoes">
                                <i class="fa icofont-plus-circle"></i> Novo inventário
                            </a>
                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos inventários do sistema (Total:{{count($inventarios)}})
                        </div>
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Númeração</th>
                                    <th>Data inventário</th>
                                    <th>Armazém</th>
                                    <th>Operador</th>
                                    <th style="text-align: center">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inventarios as $inventario)
                                    <tr>
                                        <td>{{ $inventario->numeracao }}</td>
                                        <td>{{ date_format($inventario->created_at, 'd/m/Y') }}</td>
                                        <td>{{ Str::upper($inventario->armazem->designacao) }}</td>
                                        <td>{{ Str::upper($inventario->user->name) }}</td>
                                        <td style="text-align: center">

                                                <a class="pink" title="Imprimir inventário" wire:click.prevent="imprimirInventario2({{ $inventario['id'] }})" style="cursor: pointer" >
                                                    <span wire:loading wire:target="imprimirInventario2({{ $inventario['id'] }})" class="loading"></span>

                                                    <i class="ace-icon fa fa-print bigger-150 bolder primary text-primary"></i>
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
