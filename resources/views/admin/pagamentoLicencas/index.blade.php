@php use Illuminate\Support\Str; @endphp
@section('title','Pagamento licenças')
<div class="row">
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            PAGAMENTO LICENÇAS
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
                                   class="form-control search-query" placeholder="Buscar pelo nome da empresa e nif"/>
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
                            <div style="display: flex;margin-top: 7px;">
                                <a href="{{ route('pagamentoLicencaAdminNovo') }}" title="emitir novo recibo"
                                   class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> Novo Pagamento
                                </a>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <input type="date" wire:model="filter.dataInicial" class="col-md-12" style="line-height: 22px"/>
                                </div>
                                <div class="input-group input-group-sm" style="margin-left: 10px; width: 300px" >
                                    <input type="date" wire:model="filter.dataFinal" class="col-md-12" style="line-height: 22px"/>
                                </div>
                                <a title="listar todos pagamentos" wire:click.prevent="imprimirPagamentos" href="" target="blank"
                                   class="btn btn-primary widget-box widget-color-blue url" id="botoes">
                                    <i class="fa fa-print text-default"></i> Imprimir
                                    <span wire:loading wire:target="imprimirPagamentos" class="loading">
                                    <i class="ace-icon fa fa-print bigger-160"></i>
                                </span>
                                </a>
                            </div>


                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos pagamentos de licenças
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Licença</th>
                                    <td>QTD.</td>
                                    <th>Data activação</th>
                                    <th>Data Inicio/Licença</th>
                                    <th>Data Final/Licença</th>
                                    <th>Operador</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pagamentos as $pagamento)
                                    <tr>
                                        <td> {{  Str::upper($pagamento->empresa->nome) }}</td>
                                        <td> {{  Str::upper($pagamento->fatura->descricao) }} - {{ number_format($pagamento->fatura->total_preco_factura, 2,',','.') }}</td>
                                        <td> {{  number_format($pagamento->quantidade, 1, ',', '.') }}</td>
                                        <td> {{ date("d/m/Y", strtotime($pagamento->created_at))}}</td>
                                        <td> {{ $pagamento->ativacaoLicenca?date("d/m/Y", strtotime($pagamento->ativacaoLicenca->data_inicio)):'' }}</td>
                                        <td> {{ $pagamento->ativacaoLicenca? $pagamento->ativacaoLicenca->data_fim?date("d/m/Y", strtotime($pagamento->ativacaoLicenca->data_fim)):'':'' }}</td>
                                        <td> {{ $pagamento->ativacaoLicenca?$pagamento->ativacaoLicenca->operador:'' }}</td>
                                        <td>
                                            <div>
                                            <a class="pink" title="visualizar a fatura" wire:click="visualizarFatura({{ json_encode($pagamento->id) }})" style="cursor: pointer;">
                                                <i class="ace-icon fa fa-download bigger-150 bolder orange text-orange"></i>
                                                <span wire:loading wire:target="visualizarFatura({{ json_encode($pagamento->id) }})" class="loading">
                                                    <i class="ace-icon fa fa-download bigger-150 bolder orange text-orange"></i>
                                                </span>
                                            </a>
                                            <a class="pink" title="visualizar o recibo" wire:click="visualizarRecibo({{ json_encode($pagamento->id) }})" style="cursor: pointer;">
                                                <i class="ace-icon fa fa-download bigger-150 bolder blue text-blue"></i>
                                                <span wire:loading wire:target="visualizarRecibo({{ json_encode($pagamento->id) }})" class="loading">
                                                    <i class="ace-icon fa fa-download bigger-150 bolder blue text-blue"></i>
                                                </span>
                                            </a>
                                                <a class="pink" title="visualizar comprovativo pagamento" href="{{ '/upload/'.$pagamento->comprovativo_bancario }}" target="_blank" style="cursor: pointer;">
                                                    <i class="ace-icon fa fa-eye bigger-150 bolder green text-blue"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div> {{ $pagamentos->links() }}</div>
        </div>
    </div>
</div>
