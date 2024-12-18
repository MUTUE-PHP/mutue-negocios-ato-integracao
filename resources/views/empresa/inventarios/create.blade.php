@php use Carbon\Carbon; @endphp
@section('title','Emitir inventário')
<div class="row">

    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            EMITIR INVENTÁRIO
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
                        <div class="clearfix"
                             style="display: flex;padding: 5px 5px; align-items: center; justify-content: right">
                            <div class="input-group input-group-sm" style="margin-left: 10px; width: 100%">
                                <div class="input-group input-group-sm">
                                    <input type="text" wire:model.debounce.500ms="filter.search" autofocus
                                           autocomplete="on" class="form-control search-query"
                                           placeholder="Buscar nome do produto/Código"/>
                                    <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-lg upload">
                                        <span class="ace-icon fa fa-search icon-on-right bigger-130"></span>
                                    </button>
                                </span>
                                </div>
                            </div>
                            <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                <select wire:model="filter.armazemId" data="armazemId" class="col-md-12 select2">
                                    @foreach($armazens as $armazem)
                                        <option
                                            value="{{ $armazem->id }}">{{ Str::upper($armazem->designacao) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                <select wire:model="filter.centroCustoId" data="centroCustoId"
                                        class="col-md-12 select2">
                                    @foreach($centroscusto as $centroCusto)
                                        <option
                                            value="{{ $centroCusto->id }}">{{ Str::title($centroCusto->nome) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-left: 10px;">
                                <a href="#" wire:click.prevent="emitirInventario" title="emitir o inventário"
                                   class="btn btn-success widget-box widget-color-blue botoes">
                                    <span wire:loading wire:target="emitirInventario" class="loading"></span>
                                    Emitir o inventário
                                </a>
                            </div>
                            <div style="margin-left: 10px;">
                                <a title="imprimir inventário" href="#" wire:click.prevent="imprimirInventario"
                                   class="btn btn-primary widget-box widget-color-blue botoes">
                                    <span wire:loading wire:target="imprimirInventario" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir
                                </a>
                            </div>
                        </div>


                        <div class="table-header widget-header">
                            Emitir inventário
                        </div>
                        <div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th style="text-align: right">Existência stock</th>
                                    <th style="text-align: right">Nova existência</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($produtos as$key=> $produtoData)
                                    <tr>
                                        <td>{{ $produtoData->produto->referencia }}</td>
                                        <td>{{ \Illuminate\Support\Str::upper($produtoData->produto->designacao) }}</td>
                                        <td style="text-align: right">{{ number_format($produtoData->quantidade, 1, ',', '.') }}</td>
                                        <td style="text-align: right">
                                            <input wire:model="produtosAtual.{{ $loop->index }}.quantidade"  type="number">
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
