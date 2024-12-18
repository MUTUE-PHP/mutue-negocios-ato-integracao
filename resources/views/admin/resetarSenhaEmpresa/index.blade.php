@section('title','Log de alteração senha')
<div class="row">
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            HISTÓRICO DE ALTERAÇÃO DE SENHA
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

                            <input type="text" wire:model="search" autofocus autocomplete="on" class="form-control search-query" placeholder="Buscar cliente" />
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
                            <a href="{{ route('resetarSenhaClienteCreate') }}" title="emitir novo recibo" class="btn btn-success widget-box widget-color-blue" id="botoes">
                                <i class="fa icofont-plus-circle"></i> Resetar senha
                            </a>
                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos logs de alteração de senha
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Nova credênciais</th>
                                        <th>Operador</th>
                                        <th>Data alteração</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($logsUpdatePasswordClient as $log)
                                    <tr>
                                        <td>{{  \Illuminate\Support\Str::upper($log->empresa->nome) }}</td>
                                        <td>{{ $log->password }}</td>
                                        <td>{{ $log->user->name }}</td>
                                        <td>{{ $log->created_at }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            {{ $logsUpdatePasswordClient->links() }}
        </div>
    </div>
</div>
