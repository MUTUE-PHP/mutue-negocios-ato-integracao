@php use Carbon\Carbon; @endphp
@section('title','Emitir nota de entrega')
<div class="row">
    <div class="modal fade" id="modalEmitirNotaEntrega" wire:ignore>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller">
                        EMITIR NOTA ENTREGA
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="left: 0%; position: relative;">
                        <div class="col-md-12">
                            <form class="filter-form form-horizontal validation-form">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <span>{{ $errors->all()[0] }}</span>
                                    </div>
                                @endif
                                <div class="second-row">
                                    <div class="tabbable">
                                        <div class="tab-content profile-edit-tab-content">
                                            <div id="dados_motivo" class="tab-pane in active">
                                                <div class="form-group has-info">
                                                    <div class="col-md-12">
                                                        <label class="control-label bold label-select2" for="cliente">Número documento</label>
                                                        <div>
                                                            <input type="text" wire:model.upper="numeracaoDocumento" id="cliente"
                                                                   class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-9">
                                                <button class="search-btn" style="border-radius: 10px"
                                                        wire:click.prevent="emitirNotaEntrega">

                                                    <span wire:loading.remove wire:target="emitirNotaEntrega">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        EMITIR NOTA
                                                    </span>
                                                    <span wire:loading wire:target="emitirNotaEntrega">
                                                        <span class="loading"></span>
                                                        Aguarde...</span>
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOTAS DE ENTREGA
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
                                   class="form-control search-query"
                                   placeholder="Buscar numeração do documento"/>
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
                            <a title="emitir nota entrega" class="btn btn-success widget-box widget-color-blue" href="#modalEmitirNotaEntrega" data-toggle="modal" class="pink" id="botoes">
                                <i class="fa icofont-plus-circle"></i> Nova nota entrega
                            </a>

                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todas notas entregas emitidas no sistema
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Número documento</th>
                                    <th>Operador</th>
                                    <th>Data Emissão</th>
                                    <th style="text-align: center;">Ações</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notasEntregas as $key=> $notaEntrega)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $notaEntrega->numeracao_documento }}</td>
                                        <td>{{ \Illuminate\Support\Str::upper($notaEntrega->operador_nome) }}</td>
                                        <td>{{ date("d/m/Y", strtotime($notaEntrega->created_at)) }}</td>
                                        <td style="text-align: center;">
                                            <a class="blue" wire:click="imprimirNotaEntrega({{$notaEntrega->factura_id}})"
                                               title="reimprimir nota de entrega" style="cursor: pointer">
                                                <i class="fa fa-print bigger-150 primary"></i>
                                                <span wire:loading
                                                      wire:target="imprimirNotaEntrega({{$notaEntrega->factura_id}})"
                                                      class="loading">
                                                    <i class="fa fa-print bigger-150 primary"></i>
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
                {{ $notasEntregas->links() }}
            </div>

        </div>

    </div>
</div>
