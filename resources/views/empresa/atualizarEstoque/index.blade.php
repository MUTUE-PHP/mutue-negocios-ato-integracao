<?php

use Illuminate\Support\Str;

?>
@section('title','Atualizações de estoque')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                ATUALIZAÇÕES DE ESTOQUE
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

                                <input type="text" wire:model.debounce.1000ms="filtro.search" autofocus class="form-control search-query"
                                       placeholder="buscar pelo nome do produto"/>
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
                                <a href="{{ route('atualizarStockCreate') }}" title="emitir novo recibo"
                                   class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> ATUALIZAR ESTOQUE
                                </a>

                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todas atualizações de estoque do sistema (Total:{{count($atualizacoesEstoque)}})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Operador</th>
                                        <th>Armazém</th>
                                        <th style="text-align: right">Qtd.Anterior</th>
                                        <th style="text-align: right">Qtd.Nova</th>
                                        <th style="text-align: right">Data atualização</th>
                                        <th style="text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($atualizacoesEstoque as $atualizacaoEstoque)
                                        <tr>
                                            <td>{{ Str::upper($atualizacaoEstoque->produtos->designacao) }}</td>
                                            <td>{{ Str::upper($atualizacaoEstoque->user?$atualizacaoEstoque->user->name:'') }}</td>
                                            <td>{{ Str::upper($atualizacaoEstoque->armazens->designacao) }}</td>
                                            <td style="text-align: right">{{ number_format($atualizacaoEstoque->quantidade_antes, 1, ',', '.') }}</td>
                                            <td style="text-align: right">{{ number_format($atualizacaoEstoque->quantidade_nova, 1, ',', '.') }}</td>
                                            <td style="text-align: right">{{ date("d/m/Y", strtotime($atualizacaoEstoque->created_at)) }}</td>

                                            <td style="text-align: center">
                                                <a class="blue" wire:click="imprimirEstoque({{$atualizacaoEstoque->id}})" title="Imprimir atualizar estoque" style="cursor: pointer">
                                                    <i class="ace-icon fa fa-print bigger-150 bolder primary text-primary"></i>
                                                    <span wire:loading wire:target="imprimirEstoque({{$atualizacaoEstoque->id}})" class="loading">
                                                    <i class="ace-icon fa fa-print bigger-150 bolder primary text-primary"></i>
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
                    <div>{{ $atualizacoesEstoque->links() }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
