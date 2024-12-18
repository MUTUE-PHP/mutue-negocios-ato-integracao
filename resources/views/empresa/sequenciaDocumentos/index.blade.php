@section('title','Numeração documentos')
<div class="row">

    <div class="modal fade" id="modalSalvarSequenciaDoc" wire:ignore>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller">
                        <i class="ace-icon fa fa-plus-circle bigger-150 blue"></i> NOVA SEQUÊNCIA DO DOCUMENTO
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
                                                        <label class="control-label bold label-select2" for="sequencia">Número da sequência<b class="red fa fa-question-circle"></b></label>
                                                        <div>
                                                            <input type="number" wire:model="documento.sequencia" id="sequencia" class="col-md-12 col-xs-12 col-sm-4" />
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-9">
                                                <button class="search-btn" style="border-radius: 10px" wire:click.prevent="salvarSequenciaDocumento">

                                                    <span wire:loading.remove wire:target="salvarSequenciaDocumento">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                    <span wire:loading wire:target="salvarSequenciaDocumento">
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
            INICIALIZAR O NÚMERO DE SEQUÊNCIA DO DOCUMENTO
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Listagem
            </small>
        </h1>
    </div>

    <div class="col-md-12">
        <div class>
            <form class="form-search">
                <div class="row">
                    <div class>
                        <div class="input-group input-group-sm" style="margin-bottom: 10px">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-search"></i>
                            </span>

                            <input type="text" wire:model="search" autofocus autocomplete="on" class="form-control search-query" placeholder="Buscar documento" />
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

        <div>
            <div class="row">
                <form id="adimitirCandidatos" method="POST" action>
                    <input type="hidden" name="_token" value />

                    <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                        <div class="clearfix">
                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Todos documentos (Total:{{count($sequenciaDocumentos)}})
                        </div>
                        <div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th style="text-align: right;">Ultima Sequência</th>
                                        <th style="text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sequenciaDocumentos as $sequenciaDocumento)
                                    <tr>
                                        <td>{{$sequenciaDocumento['tipoDocumentoNome']}}</td>
                                        <td style="text-align: right;">{{$sequenciaDocumento['sequencia']}}</td>
                                        <td style="text-align: center;">
                                            <div class="hidden-sm hidden-xs action-buttons">
                                                <a  href="#modalSalvarSequenciaDoc" data-toggle="modal" wire:click.prevent="modalSalvarSequencia({{ json_encode($sequenciaDocumento) }})"  class="pink" title="Botão para inicializar a sequência do documento">
                                                    <i class="fa fa-pencil-square-o bigger-200 blue"></i>
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

        </div>

    </div>
</div>

