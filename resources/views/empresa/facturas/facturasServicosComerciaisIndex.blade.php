@section('title', 'Facturas serviços comerciais')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                FACTURAS - SERVIÇOS COMERCIAIS
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
                                <input type="text" wire:model="filter.search" autofocus autocomplete="on"
                                       class="form-control search-query"
                                       placeholder="Buscar pela numeração da factura"/>
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

                                <div class="clearfix" style="display: flex;padding: 5px 5px; align-items: center">   <a title="imprimir faturas Comercias" href="#" wire:click.prevent="imprimirServicoComercias('pdf')" class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="imprimirServicoComercias('pdf')" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir PDF
                                </a>
                                <a title="imprimir faturas aeroportuário" href="#" wire:click.prevent="imprimirServicoComercias('xls')" class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="imprimirServicoComercias('xls')" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir EXCEL
                                </a> </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <select wire:model="filter.tipoDocumentoId" data="tipoDocumentoId"
                                            class="col-md-12 select2">
                                        <option value="">Filtrar tipo documento</option>
                                        <option value="1">FATURA RECIBO</option>
                                        <option value="2">FATURA</option>
                                        <option value="3">FATURA PROFORMA</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataInicial" class="col-md-12"
                                           style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataFinal" class="col-md-12"
                                           style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <select wire:model="filter.centroCustoId" data="centroCustoId"
                                            class="col-md-12 select2">
                                        <option value="">Filtrar pelo centro de custo</option>
                                        @foreach($centrosCusto as $centroCusto)
                                            <option
                                                value="{{ $centroCusto->id }}">{{ Str::title($centroCusto->nome) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <select wire:model="filter.orderBy" data="orderBy" class="col-md-12 select2">
                                        <option value="ASC" <?= $filter['orderBy'] == 'ASC' ? 'selected' : null ?>>ASC
                                        </option>
                                        <option value="DESC" <?= $filter['orderBy'] == 'DESC' ? 'selected' : null ?>>
                                            DESC
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-header widget-header">
                                Todas as facturas do sistema Total: <strong>{{$totalFactura}}</strong>
                            </div>
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Numeração</th>
                                        <th style="text-align: right">Total</th>
                                        <th>Emitido</th>
                                        <th>Cliente</th>
                                        <th>Telefone</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($facturas as $key=>$factura)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $factura['numeracaoFactura'] }}</td>
                                            <td style="text-align: right">{{ number_format($factura['total'],2,',','.') }}</td>

                                            <td>{{ date_format($factura['created_at'], 'd/m/Y') }}</td>
                                            <td>{{ $factura['nome_do_cliente'] }}</td>
                                            <td>{{ $factura['telefone_cliente'] }}</td>
                                            <td style="text-align: center">
                                                    <span
                                                        class="label label-sm <?= $factura['anulado'] == 'Y'?'label-danger':'label-success'?>">{{ $factura['anulado'] == 'Y'?"Anulada":"Válida" }}
                                                    </span>
                                            </td>
                                            <td style="text-align: center">
                                                <a class="blue"
                                                   wire:click="imprimirFactura({{ $factura['id'] }})"
                                                   title="Reimprimir o factura" style="cursor: pointer">
                                                    <i class="ace-icon fa fa-print bigger-160"></i>
                                                    <span wire:loading
                                                          wire:target="imprimirFactura({{ $factura['id'] }})"
                                                          class="loading">
                                                            <i class="ace-icon fa fa-print bigger-160"></i>
                                                        </span>
                                                </a>
                                                @if($factura['anulado'] != 'Y')
                                                    <a href="{{ route('anulacaoFaturaServicoComercialCreate', $factura['id']) }}"
                                                       class="pink"
                                                       title="anulação de documento">
                                                        <i class="ace-icon fa fa-times-circle-o bigger-160 bolder danger text-danger"></i>
                                                    </a>
                                                    <a href="{{ route('retificacaoFaturaServicoComercialCreate', $factura['id']) }}"
                                                       class="pink"
                                                       title="retificação de documento">
                                                        <i class="ace-icon fa fa-retweet bigger-150 bolder success text-success"></i>
                                                    </a>
                                                @endif
                                                @if($factura['anulado'] == 'Y')
                                                    <a class="red"
                                                       wire:click="imprimirFacturaAnulado({{ $factura['notaCredito']['id'] }})"
                                                       title="Reimprimir o factura anulada" style="cursor: pointer">
                                                        <i class="ace-icon fa fa-print bigger-160"></i>
                                                        <span wire:loading
                                                              wire:target="imprimirFacturaAnulado({{ $factura['notaCredito']['id'] }})"
                                                              class="loading">
                                                            <i class="ace-icon fa fa-print bigger-160"></i>
                                                        </span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    {{ $facturas->links() }}
                </div>

            </div>
        </div>
    </div>

</div>
