@php use Illuminate\Support\Str; @endphp
@section('title','Especificação de mercadorias')

<div>
    <div class="row">
        <div class="modal fade" id="modalCriarTipoMercadoria" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            NOVA ESPECIFICAÇÃO DE MERCADORIA
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
                                                                   for="valor">Desconto</label>
                                                            <div>
                                                                <input type="number" wire:model="especificacao.desconto"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="statuId">Status</label>
                                                            <select wire:model="especificacao.status" data="status"
                                                                    class="col-md-12 select2" id="status"
                                                                    style="height:35px;<?= $errors->has('especificacao.status') ? 'border-color: #ff9292;' : '' ?>">
                                                                <option value="1">ATIVO</option>
                                                                <option value="2">DESATIVO</option>

                                                            </select>
                                                            @if ($errors->has('especificacao.status'))
                                                                <span class="help-block"
                                                                      style="color: red; font-weight: bold">
                                                                    <strong>{{ $errors->first('especificacao.status') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="col-md-12">

                                                            <label class="control-label bold label-select2" for="saldoAtual">Descrição <b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group">
                                                                <textarea wire:model="especificacao.designacao" id="" cols="200" rows="4" class="form-control" style="font-size: 16px; z-index: 1;"></textarea>
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
                            EDITAR ESPECIFICAÇÃO
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
                                                                   for="valor">Desconto</label>
                                                            <div>
                                                                <input type="number" wire:model="especificacao.desconto"
                                                                       id="valor" class="col-md-12 col-xs-12 col-sm-4"/>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="control-label bold label-select2"
                                                                   for="statuId">Status</label>
                                                            <select wire:model="especificacao.status" data="status"
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

                                                        <div class="col-md-12">

                                                            <label class="control-label bold label-select2" for="saldoAtual">Descrição <b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group">
                                                                <textarea wire:model="especificacao.designacao" id="" cols="200" rows="4" class="form-control" style="font-size: 16px; z-index: 1;"></textarea>
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
                ESPECIFICAÇÃO DE MERCADORIAS
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
                                       placeholder="Buscar especificação"/>
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
{{--                                <a href="#modalCriarTipoMercadoria" data-toggle="modal"--}}
{{--                                   class="btn btn-success widget-box widget-color-blue" id="botoes"--}}
{{--                                   wire:click="resetField()"--}}
{{--                                   >--}}
{{--                                    <i class="fa icofont-plus-circle"></i> Nova Especificação--}}
{{--                                </a>--}}
{{--                                <a title="Imprimir Especificações mercadorias" wire:click.prevent="imprimirEspecificacao"--}}
{{--                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">--}}
{{--                                    <span wire:loading wire:target="imprimirEspecificacao" class="loading"></span>--}}
{{--                                    <i class="fa fa-print text-default"></i> Imprimir--}}
{{--                                </a>--}}
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                               Todas as especificação de mercadorias do sistema (Total: {{ count($countespecificacaoMercadorias) }})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descrição</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: right">Desconto(%)</th>
{{--                                        <th style="text-align: center">Ações</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($especificacaoMercadorias as $key=> $especificacao)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ Str::upper($especificacao->designacao) }}</td>

                                            <td class="hidden-480" style="text-align: center">
                                                <span
                                                    class="label label-sm <?= $especificacao['status']== 1 ? 'label-success' : 'label-warning' ?>"
                                                    style="border-radius: 20px;">{{$especificacao['status'] == 1 ? 'Activo' : 'Inactivo' }}</span>
                                            </td>

                                            <td style="text-align: right">{{ number_format($especificacao->desconto, 2, ',', '.') }}</td>
{{--                                            <td style="text-align: center">--}}

{{--                                                <div class="hidden-sm hidden-xs action-buttons">--}}

{{--                                                    <a wire:click="edit({{$especificacao->id}})" href="#modalEditarTipoMercadoria" data-toggle="modal"--}}
{{--                                                       class="pink" title="Editar este registo">--}}
{{--                                                        <i class="fa fa-pencil-square-o bigger-200 blue"></i>--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
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
            $('#modalEditarTipoMercadoria').modal('hide');
        })
    </script>
@endpush
