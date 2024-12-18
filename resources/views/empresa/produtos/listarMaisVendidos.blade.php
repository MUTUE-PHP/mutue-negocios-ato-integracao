<?php
use Illuminate\Support\Str;
?>
@section('title','Produtos mais vendidos')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                PRODUTOS MAIS VENDIDOS
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
                            <div class="clearfix" style="display: flex;justify-content: right;margin: 3px">
                                <a title="imprimir produtos" wire:click.prevent="imprimirProdutosMaisVendidos"
                                   class="btn btn-primary widget-box widget-color-blue botoes">
                                    <span wire:loading wire:target="imprimirProdutosMaisVendidos" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir
                                </a>
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todos os produtos mais vendidos (Total:{{count($produtos)}})
                            </div>
                            <div>
                                <table class=" tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th style="text-align:right">Preço Venda</th>
                                        <th style="text-align:right">Preço Compra</th>
                                        <th style="text-align:right">Quant/Vendidas</th>
                                        <th style="text-align:center">Estocavel?</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($produtos as $produto)
                                        <tr>
                                            <td>{{ Str::upper($produto->designacao)}}</td>
                                            <td style="text-align:right">{{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                                            <td style="text-align:right">{{ number_format($produto->preco_compra, 2, ',', '.') }}</td>
                                            <td style="text-align:right">{{ number_format($produto->qtVendidos, 1, ',', '.') }}</td>
                                            <td style="text-align: center">
                                                @if($produto->stocavel === 'Sim')
                                                    <span
                                                        class="label label-sm label-success">{{ $produto->stocavel }}</span>
                                                @else
                                                    <span
                                                        class="label label-sm label-warning">{{ $produto->stocavel }}</span>
                                                @endif
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
