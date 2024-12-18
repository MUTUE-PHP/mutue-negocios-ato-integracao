@php use Illuminate\Support\Str; @endphp
@section('title','Tipos de mercadorias')

<div>
    <div class="row">
        <div class="modal fade" id="modalCriarTipoMercadoria" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            NOVO TIPO DE MERCADORIAS
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
                                                                   for="designacao">Nome</label>
                                                            <div>
                                                                <input type="text" wire:model="mercadoria.designacao"
                                                                       id="designacao"
                                                                       class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="valor">Taxa</label>
                                                            <div>
                                                                <input type="number" wire:model="mercadoria.taxa"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="statuId">Status</label>
                                                            <select wire:model="mercadoria.statuId" data="statuId"
                                                                    class="col-md-12 select2" id="statuId"
                                                                    style="height:35px;<?= $errors->has('marca.status_id') ? 'border-color: #ff9292;' : '' ?>">
                                                                <option value="1">ATIVO</option>
                                                                <option value="2">DESATIVO</option>

                                                            </select>
                                                            @if ($errors->has('mercadoria.statuId'))
                                                                <span class="help-block"
                                                                      style="color: red; font-weight: bold">
                                                                    <strong>{{ $errors->first('mercadoria.statuId') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="salvarTipoMercadoria">

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
                            EDITAR TIPO MERCADORIA
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
                                                                   for="designacao">Nome</label>
                                                            <div>
                                                                <input type="text" wire:model="mercadoria.designacao"
                                                                       id="designacao"
                                                                       class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="valor">Taxa</label>
                                                            <div>
                                                                <input type="number" wire:model="mercadoria.taxa"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="statuId">Status</label>
                                                            <select wire:model="mercadoria.statuId" data="statuId"
                                                                    class="col-md-12 select2" id="statuId"
                                                                    style="height:35px;<?= $errors->has('marca.status_id') ? 'border-color: #ff9292;' : '' ?>">
                                                                <option value="1">ATIVO</option>
                                                                <option value="2">DESATIVO</option>

                                                            </select>
                                                            @if ($errors->has('mercadoria.statuId'))
                                                                <span class="help-block"
                                                                      style="color: red; font-weight: bold">
                                                                    <strong>{{ $errors->first('mercadoria.statuId') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="update">

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

        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                TIPOS DE MERCADORIAS
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
                                       placeholder="Buscar pelo nome da categoria"/>
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
{{--                                @if(Auth::user()->hasPermission('gerir mercadorias') || Auth::user()->isSuperAdmin())--}}
{{--                                    <a href="#modalCriarTipoMercadoria" data-toggle="modal"--}}
{{--                                       class="btn btn-success widget-box widget-color-blue" id="botoes">--}}
{{--                                        <i class="fa icofont-plus-circle"></i> Novo tipo de mercadoria--}}
{{--                                    </a>--}}
{{--                                @endif--}}
                                <a title="Imprimir Tipos de mercadorias" wire:click.prevent="imprimirTiposMercadorias"
                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="imprimirTiposMercadorias" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir
                                </a>
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todos tipos de mercadorias do sistema (Total: {{ count($tiposMercadorias) }})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Designação</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: right">Taxa</th>
                                        <th style="text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tiposMercadorias as $key=> $mercadoria)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ Str::upper($mercadoria['designacao']) }}</td>

                                            <td class="hidden-480" style="text-align: center">
                                                <span
                                                    class="label label-sm <?= $mercadoria['statuId']== 1 ? 'label-success' : 'label-warning' ?>"
                                                    style="border-radius: 20px;">{{$mercadoria['statuId'] == 1 ? 'Activo' : 'Inactivo' }}</span>
                                            </td>

                                            <td style="text-align: right">{{ number_format($mercadoria['taxa'], 2, ',', '.') }}</td>
                                            <td style="text-align: center">

                                                <div class="hidden-sm hidden-xs action-buttons">
                                                    @if(Auth::user()->hasPermission('gerir mercadorias') || Auth::user()->isSuperAdmin())
                                                        <a wire:click="edit({{$mercadoria->id}})"
                                                           href="#modalEditarTipoMercadoria" data-toggle="modal"
                                                           class="pink" title="Editar este registo">
                                                            <i class="fa fa-pencil-square-o bigger-200 blue"></i>
                                                        </a>
                                                    @endif
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

</div>
