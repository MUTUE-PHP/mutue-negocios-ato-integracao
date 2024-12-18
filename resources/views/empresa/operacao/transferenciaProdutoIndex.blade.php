@section('title','Transferencias de produtos')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                TRANSFERENCIA DE PRODUTOS
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
                                       class="form-control search-query" placeholder="Buscar..."/>
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
                                <a href="{{ route('transferenciaNova') }}" title="emitir novo recibo"
                                   class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> Nova transferencia
                                </a>
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todas transferencia do sistema (Total:{{count($transferencias)}})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Numeração</th>
                                        <th>Operador</th>
                                        <th>Data</th>
                                        <th style="text-align: center;">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transferencias as $key=> $transferencia)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{$transferencia['numeracao_transferencia']}}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($transferencia['user']['name'], 25)  }}</td>
                                            <td>{{ date_format($transferencia['created_at'], 'd/m/Y') }}</td>
                                            <td style="text-align: center">
{{--                                                <a class="blue" wire:click="imprimirTransferencia({{ $transferencia['id'] }})"--}}
{{--                                                   title="Visualizar detalhes" style="cursor: pointer">--}}
{{--                                                    <i class="ace-icon fa fa-eye bigger-160" style="color: lightcoral"></i>--}}
{{--                                                </a>--}}
                                                <a class="blue" wire:click="imprimirTransferencia({{ $transferencia['id'] }})"
                                                   title="Reimprimir a transferencia" style="cursor: pointer">
                                                    <i class="ace-icon fa fa-print bigger-160"></i>
                                                    <span wire:loading
                                                          wire:target="imprimirTransferencia({{ $transferencia['id'] }})"
                                                          class="loading">
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
                        {{ $transferencias->links() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
