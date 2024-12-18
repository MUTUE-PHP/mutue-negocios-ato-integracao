@section('title','Recibos anulados')
<div class="row">
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            RECIBOS ANULADOS
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
                        <div class="clearfix">
                            <a href="/empresa/anulacao/recibo/novo" title="emitir novo recibo"
                               class="btn btn-success widget-box widget-color-blue" id="botoes">
                                <i class="fa icofont-plus-circle"></i> ANULAR RECIBOS
                            </a>

                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos recibos anulados do sistema (Total:{{ count($recibos) }})
                        </div>
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nº Documento</th>
{{--                                    <th>Recibo referente</th>--}}
                                    <th>Nome do cliente</th>
                                    <th>Operador</th>
                                    <th style="text-align: right">Total Factura</th>
                                    <th>Emitido</th>
{{--                                    <th style="text-align: center">Ações</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recibos as $key=>$rc)
                                    <tr>
                                        <td>{{ ++$key }}</td>
{{--                                        <td>{{ $rc['numDoc'] }}</td>--}}
                                        <td>{{ $rc['recibo']['numeracaoRecibo'] }}</td>
                                        <td>{{ $rc['recibo']['nomeCliente'] }}</td>
                                        <td>{{ $rc['user']['name'] }}</td>
                                        <td style="text-align: right">{{ number_format($rc['recibo']['totalFatura'], 2, ',', '.') }}</td>
                                        <td>{{date_format($rc->created_at,'d/m/Y')}}</td>
{{--                                        <td style="text-align: center">--}}
{{--                                            <a class="blue" wire:click="printNotaCredito({{$rc->id}})"--}}
{{--                                               title="Reimprimir nota credito" style="cursor: pointer">--}}
{{--                                                <i class="ace-icon fa fa-print bigger-160"></i>--}}
{{--                                                <span wire:loading wire:target="printNotaCredito({{$rc->id}})"--}}
{{--                                                      class="loading">--}}
{{--                                                    <i class="ace-icon fa fa-print bigger-160"></i>--}}
{{--                                                </span>--}}
{{--                                            </a>--}}
{{--                                        </td>--}}
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
