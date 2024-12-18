<?php

use Illuminate\Support\Str;

?>

@section('title','Produtos/Serviços')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                PRODUTOS/SERVIÇOS
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

                                <input type="text" wire:model.debounce.1000ms="filter.search" autofocus
                                       autocomplete="on" class="form-control search-query"
                                       placeholder="Buscar nome do produto"/>
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
                            <div class="clearfix" style="display: flex;padding: 5px 5px; align-items: center">
                                <div>
{{--                                    <a href="{{ route('produto.create') }}" title="adicionar novo produto"--}}
{{--                                       class="btn btn-success widget-box widget-color-blue botoes">--}}
{{--                                        <i class="fa icofont-plus-circle"></i> Novo produto--}}
{{--                                    </a>--}}
                                    <a title="imprimir produtos" href="#" wire:click.prevent="imprimirProdutos"
                                       class="btn btn-primary widget-box widget-color-blue botoes">
                                        <span wire:loading wire:target="imprimirProdutos" class="loading"></span>
                                        <i class="fa fa-print text-default"></i> Imprimir
                                    </a>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 180px">
                                    <select wire:model="filter.vendasOnline" data="vendasOnline"
                                            class="col-md-12 select2">
                                        <option value="">Mostrar todos</option>
                                        @if(auth()->user()->empresa->venda_online == 'Y')
                                            <option value="Y">Produto vendas online</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <select wire:model="filter.centroCustoId" data="centroCustoId"
                                            class="col-md-12 select2">
                                        @foreach($centrosCusto as $centroCusto)
                                            <option
                                                value="{{ $centroCusto->id }}" <?= $filter['centroCustoId'] == $centroCusto->id ? 'selected' : '' ?>>{{ Str::title($centroCusto->nome) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-header widget-header">
                                Todos os produtos/serviços do sistema Total: <strong>{{ $totalProduto }}</strong>

                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nome</th>
                                        <th>Tipo Serviço</th>
                                        <th style="text-align:right">Taxa</th>
                                        <th style="text-align:center">Estocavel</th>
                                        <th style="text-align: center">Estado</th>
                                        <th style="text-align: center;width: 100px">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($produtos as $produto)
                                        <tr>
                                            <td>{{ Str::upper($produto['referencia'])}}</td>
                                            <td>{{ Str::upper($produto['designacao'])}}</td>
                                            <td>{{ Str::upper($produto['tipoServico']['designacao'])}}</td>
                                            <td style="text-align:right">{{ $produto['tipoTaxa']['descricao'] }}</td>
                                            <td style="text-align:center">
                                                @if($produto['stocavel'] == 'Sim')
                                                    <span
                                                        class="label label-sm label-success">{{ $produto['stocavel']}}</span>
                                                @else
                                                    <span
                                                        class="label label-sm label-warning">{{ $produto['stocavel']}}</span>
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                @if($produto['status_id'] == 1)
                                                    <span
                                                        class="label label-sm label-success">{{ $produto['statuGeral']['designacao']}}</span>
                                                @else
                                                    <span
                                                        class="label label-sm label-warning">{{ $produto['statuGeral']['designacao']}}</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                <a href="{{ route('produto.edit', $produto['uuid']) }}">
                                                    <i class="fa fa-pencil-square-o bigger-150 blue"
                                                       title="Editar Produtos"></i>

                                                </a>
                                                @if(Auth::user()->hasPermission('gerir produtos') || Auth::user()->isSuperAdmin())
                                                    <a title="Eliminar este Registro" style="cursor:pointer;"
                                                       wire:click="modalDel({{$produto->id}})">
                                                        <i class="ace-icon fa fa-trash-o bigger-150 bolder danger red"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>{{$produtos->links()}}</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            // Impedir o envio de formulário padrão, se aplicável
            event.preventDefault();

            // Acionar o evento de clique no botão quando a tecla "Enter" for pressionada
            // document.getElementById("submitButton").click();
        }
    });
</script>
