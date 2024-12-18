@section('title', 'Facturas de cargas')
<div>
    <div class="row">
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                LOGS DE ACESSO
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
                                {{--                                <a title="imprimir faturas de cargas" href="#"--}}
                                {{--                                   wire:click.prevent="imprimirFaturasCarga('pdf')"--}}
                                {{--                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">--}}
                                {{--                                    <span wire:loading wire:target="imprimirFaturasCarga('pdf')" class="loading"></span>--}}
                                {{--                                    <i class="fa fa-print text-default"></i> Imprimir PDF--}}
                                {{--                                </a>--}}
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataInicial" class="col-md-12"
                                           style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px">
                                    <input type="date" wire:model="filter.dataFinal" class="col-md-12"
                                           style="line-height: 22px"/>
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
                                Todos logs de acesso
                            </div>
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 600px">Descricao</th>
                                        <th>Rota acessado</th>
                                        <th>Operador</th>
                                        <th>IP</th>
                                        <th style="text-align: center">Data de acesso</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($logsAcesso as $key=> $log)

                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td style="width: 600px">{{ $log->descricao }}</td>
                                            <td>{{ $log->rota_acessado }}</td>
                                            <td>{{ $log->user_name }}</td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ date_format($log['created_at'], 'd/m/Y H:m:s') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    {{ $logsAcesso->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
