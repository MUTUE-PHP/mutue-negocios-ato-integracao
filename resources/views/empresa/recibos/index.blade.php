@section('title','Recibos')
<div class="row">

    <div id="visualizarComprovativo" class="modal fade" wire:ignore.self>
        <div class="modal-dialog modal-lg" style="display: flex;justify-content: center">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller"><i class="ace-icon fa fa-plus-circle bigger-150 blue"></i> COMPROVATIVO ANEXADO</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="left: 0%; position: relative;">
                    <iframe src="{{ $comprovativoBancario}}" width="800" height="800" style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            RECIBOS
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

                            <input type="text" wire:model="search" autofocus autocomplete="on" class="form-control search-query" placeholder="Buscar por nome do cliente, numeração do recibo" />
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
                    <input type="hidden" name="_token" value />

                    <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                        <div class="clearfix">
                            
                            <div class="clearfix" style="display: flex;padding: 5px 5px; align-items: center">
                                <div> 
                                <a href="{{ route('recibos.create') }}" title="emitir novo recibo" class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> Novo recibo
                                </a>
                                </div>
                                <a title="imprimir Recibos" href="#"
                                   wire:click.prevent="ImprimirRelatoriosRecibos('pdf')"
                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="ImprimirRelatoriosRecibos('pdf')" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir PDF
                                </a>
                                <a title="imprimir Recibos" href="#"
                                   wire:click.prevent="ImprimirRelatoriosRecibos('xls')"
                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="ImprimirRelatoriosRecibos('xls')" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir EXCEL
                                </a>
                                
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataInicial" class="col-md-12"
                                           style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataFinal" class="col-md-12"
                                           style="line-height: 22px"/>
                                </div>
                            </div>
                            <div class="pull-right tableTools-container"></div>

                          
                        </div>

                       
                        <div class="table-header widget-header">
                            Todas recibos do sistema (Total:{{count($recibos)}})
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nº Recibo</th>
                                        <th>Factura referente</th>
                                        <th>Nome do cliente</th>
                                        <th style="text-align: right">Valor entregue</th>
                                        <th>Forma pagamento</th>
                                        <th>Emitido</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recibos as $recibo)
                                    <tr>
                                        <td>{{$recibo->numeracaoRecibo}}</td>
                                        <td>{{$recibo->factura->numeracaoFactura}}</td>
                                        <td>{{$recibo->nomeCliente}}</td>
                                        <td style="text-align: right">{{number_format($recibo->totalEntregue, 2, ',','.')}}</td>
                                        <td>{{$recibo->formaPagamento->descricao}}</td>
                                        <td>{{date_format($recibo->created_at,'d/m/Y')}}</td>
                                        <td style="text-align: center">
                                            <span class="label label-sm <?= $recibo->anulado == 'N' ? 'label-success' : 'label-danger' ?>"><?= $recibo->anulado == 'Y' ? "Anulado" : "Válido" ?></span>
                                        </td>
                                        <td style="text-align: center">
                                            <a class="blue" wire:click="printRecibo({{$recibo->id}})" title="Reimprimir o recibo" style="cursor: pointer">
                                                <i class="ace-icon fa fa-print bigger-160"></i>
                                                <span wire:loading wire:target="printRecibo({{$recibo->id}})" class="loading">
                                                    <i class="ace-icon fa fa-print bigger-160"></i>
                                                </span>
                                            </a>
                                            @if($recibo['comprovativoBancario'])
                                            <a class="blue ml-4" href="#visualizarComprovativo" data-toggle="modal" wire:click="visualizarComprovativo({{$recibo}})" title="ver recibo" style="cursor: pointer; margin-left:4px">
                                                <i class="ace-icon fa fa-file bigger-160"></i>
                                                <span wire:loading wire:target="printRecibo({{$recibo->id}})" class="loading">
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
                {{ $recibos->links() }}

            </div>

        </div>

    </div>
</div>
