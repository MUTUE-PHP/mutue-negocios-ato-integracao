@section('title','Detalhes do produto')
<div>
    <div class="row">
        <div class="modal fade" id="modalEditarCarateristica" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" wire:click="closeModal" class="close red bolder" data-dismiss="modal">×
                        </button>
                        <h4 class="smaller">
                            EDITAR DETALHES
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
                                                            <div>
                                                                <input type="text"
                                                                       wire:model="produtoCarateristica"
                                                                       style="font-size: 18px"
                                                                       value="{{ $editarDetalhe['designacao'] }}"
                                                                       class="col-md-6 col-xs-6 col-sm-6"/>
                                                                <button
                                                                    wire:click.prevent="editarCarateristica({{ $editarDetalhe['id'] }})"
                                                                    style="width:43px;height:40px;">+
                                                                </button>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-12">
                                                            <div><input type="text" wire:model="produtoCarateristicaItem"
                                                                        style="font-size: 18px;    font-size: 18px;margin-left: 13px;width: 358px;"
                                                                        placeholder="descrição do item acima"
                                                                        class="col-md-6 col-xs-6 col-sm-6"/>
                                                                <button
                                                                    wire:click.prevent="adicionarItemCarateristica({{ $editarDetalhe['id'] }})"
                                                                    style="width:43px;height:40px;">+
                                                                </button>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">

                                                            <div>
                                                                <ul>
                                                                    @foreach($editarDetalhe['caracteristicas'] as $carateristica)
                                                                        <li  style="margin-left: 30px; font-size: 18px">{{ $carateristica['designacao'] }}
                                                                            <i wire:click="eliminarItemCarateristica({{ $carateristica['id'] }})" style="cursor: pointer"
                                                                               class="red fa fa-trash"></i>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
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
        <div class="modal fade" id="modalAddCarateristica" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" wire:click="closeModal" class="close red bolder" data-dismiss="modal">×
                        </button>
                        <h4 class="smaller">
                            ADICIONAR DETALHES
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
                                                            <div>
                                                                <input type="text"
                                                                       wire:model="novoCarateristicaProduto"
                                                                       style="font-size: 18px"
                                                                       value="{{ $editarDetalhe['designacao'] }}"
                                                                       class="col-md-12 col-xs-12 col-sm-12"/>

                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="novaCarateristica">

                                                    <span wire:loading.remove wire:target="novaCarateristica">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                        <span wire:loading wire:target="novaCarateristica">
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
                DETALHES DO PRODUTO - {{ \Illuminate\Support\Str::upper($produto['designacao']) }}
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
                                       placeholder=""/>
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
                                <a href="#modalAddCarateristica" data-toggle="modal" title="novo detalhes do produto"
                                   class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> Nova carateristica
                                </a>


                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todos detalhes do produto
                            </div>
                            <div>

                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Descrição</th>
                                        <th>Valor descrição</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($produto['caracteristicas'] as $valorCategoria)
                                        <tr>
                                            <td>{{ $valorCategoria['id'] }}</td>
                                            <td>{{ $valorCategoria['designacao'] }}</td>
                                            <td>
                                                @foreach($valorCategoria['caracteristicas'] as$key=> $carateristica)
                                                    {{$carateristica['designacao'] }}{{ isset($valorCategoria['caracteristicas'][++$key])?",":"" }}
                                                @endforeach
                                            </td>
                                            <td>
                                                <a title="editar este Registro" href="#modalEditarCarateristica" data-toggle="modal"
                                                   wire:click="modalEditarCarateristica({{json_encode($valorCategoria) }} )"
                                                   style="cursor:pointer;">
                                                    <i class="fa fa-pencil-square-o bigger-150 blue"></i>

                                                </a>
                                                <a title="Eliminar este Registro" style="cursor:pointer;" wire:click="modalDel({{$valorCategoria['id']}})">
                                                    <i class="ace-icon fa fa-trash-o bigger-150 bolder danger red"></i>
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
</div>
