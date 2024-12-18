@php use Illuminate\Support\Str; @endphp
@section('title','Especificação de mercadorias')

<div>
    <div class="row">
        <div class="modal fade" id="modalPmd" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            {{$modalTitle}}
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
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="designacao">Tonelada Máxima</label>
                                                            <div>
                                                                <input type="number" wire:model="pmd.toneladas_max"
                                                                       id="designacao"
                                                                       class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="designacao">Tonelada Minima</label>
                                                            <div>
                                                                <input type="number" wire:model="pmd.toneladas_min"
                                                                       id="designacao"
                                                                       class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2"
                                                                   for="valor">valor</label>
                                                            <div>
                                                                <input type="number" wire:model="pmd.taxa"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="store">

                                                    <span wire:loading.remove wire:target="salvarTipoMercadoria">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                        <span wire:loading wire:target="salvarTipoMercadoria">
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

        <div class="modal fade" id="modalEditarTipoMercadoria" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            EDITAR CÂMBIO
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
                                                            <label class="control-label bold label-select2"
                                                                   for="designacao">Designação</label>
                                                            <div>
                                                                <input type="text" wire:model="pmd.designacao"
                                                                       id="designacao"
                                                                       class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2"
                                                                   for="valor">Valor</label>
                                                            <div>
                                                                <input type="number" wire:model="pmd.valor"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="store">

                                                    <span wire:loading.remove wire:target="store">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                        <span wire:loading wire:target="store">
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
                Intervalo PMD
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
                                <input type="text" wire:model.debounce.500ms="search" autofocus autocomplete="on"
                                       class="form-control search-query"
                                       placeholder="Buscar pelo designação  do câmbio"/>
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
                                <a href="#modalPmd" data-toggle="modal"
                                   class="btn btn-success widget-box widget-color-blue hidden" id="botoes"
                                   wire:click="resetField()"
                                   >
                                    <i class="fa icofont-plus-circle"></i> Novo Intervalo
                                </a>
                                <a title="Imprimir Categoria" wire:click.prevent="imprimirCategoria"
                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="imprimirCategoria" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir
                                </a>
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todos os câmbios do sistema (Total: {{ count($pmds) }})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center"> Intervalo de PMD</th>
                                        <th style="text-align: right">Taxa(USD)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pmds as $key=> $pmd)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td  class="text-center">{{ $pmd->toneladas_min.' -----------------   '.$pmd->toneladas_max }}</td>
                                            <td style="text-align: right">{{ number_format($pmd->taxa, 2, ',', '.') }}</td>

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

</div>
@push('scripts')
    <script>
        window.addEventListener('close-modal', event =>{
            $('#modalPmd').modal('hide');
        })
    </script>
@endpush
