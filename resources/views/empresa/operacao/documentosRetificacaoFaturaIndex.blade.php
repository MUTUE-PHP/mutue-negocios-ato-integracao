@section('title','Faturas retificadas')
<div class="row">
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            FATURAS RETIFICADAS
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

                        <div class="table-header widget-header">
                            Todas faturas retificadas do sistema (Total:{{ count($facturas) }})
                        </div>
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nº Documento</th>
                                    <th>Factura referente</th>
                                    <th>Nome do cliente</th>
                                    <th>Operador</th>
                                    <th style="text-align: right">Valor Iliquido</th>
                                    <th style="text-align: right">Valor Imposto</th>
                                    <th style="text-align: right">Contra Valor</th>
                                    <th>Emitido</th>
                                    <th style="text-align: center">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($facturas as $key=>$factura)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $factura['numDoc'] }}</td>
                                        <td>{{ $factura['factura']['numeracaoFactura'] }}</td>
                                        <td>{{ $factura['nome_do_cliente'] }}</td>
                                        <td>{{ $factura['operador'] }}</td>
                                        <td style="text-align: right">{{ number_format($factura['valorIliquido'],2,',','.') }}</td>
                                        <td style="text-align: right">{{ number_format($factura['valorImposto'],2,',','.') }}</td>
                                        <td style="text-align: right">{{ number_format($factura['contraValor'],2,',','.') }}(USD)</td>
                                        <td>{{ date_format($factura['created_at'], 'd/m/Y') }}</td>


                                        <td style="text-align: center">
                                            <a class="blue"
                                               wire:click="imprimirFaturaNotaCreditoRetificacao({{ $factura['id'] }})"
                                               title="Reimprimir o factura" style="cursor: pointer">
                                                <i class="ace-icon fa fa-print bigger-160"></i>
                                                <span wire:loading wire:target="imprimirFaturaNotaCreditoRetificacao({{ $factura['id'] }})" class="loading">
                                                            <i class="ace-icon fa fa-print bigger-160"></i>
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
            </div>

        </div>

    </div>
</div>
