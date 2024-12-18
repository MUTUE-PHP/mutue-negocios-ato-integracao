<?php
?>
@section('title','Perguntas frequêntes')
<div>
    <div class="row">

        <div class="modal fade" id="modalUpdatePerguntaFrequentes" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            ATUALIZAR PERGUNTA FREQUÊNTES
                        </h4>

                    </div>

                    <div class="modal-body">
                        <div class="row" style="left: 0%; position: relative;">

                            <div class="col-md-12">
                                <form class="filter-form form-horizontal validation-form">
                                    <div class="second-row">
                                        <div class="tabbable">
                                            <div class="tab-content profile-edit-tab-content">
                                                <div id="dados_motivo" class="tab-pane in active">
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2" for="cliente">Pergunta<b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group col-md-12">
                                                                <input type="text" wire:model="perguntaEdit" style="width: 100%; <?= $errors->has('perguntaEdit') ? 'border-color: #ff9292;' : '' ?>"/>
                                                            </div>
                                                            @if ($errors->has('perguntaEdit'))
                                                                <span class="help-block" style="color: red; font-weight: bold;position:absolute;"><strong>{{ $errors->first('perguntaEdit') }}</strong></span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2"
                                                                   for="numeroCartao">Resposta<b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group col-md-12">
                                                                <textarea type="text" wire:model="respostaEdit" style="width: 100%; <?= $errors->has('respostaEdit') ? 'border-color: #ff9292;' : '' ?>">
                                                                </textarea>
                                                            </div>
                                                            @if ($errors->has('respostaEdit'))
                                                                <span class="help-block" style="color: red; font-weight: bold;position:absolute;"><strong>{{ $errors->first('respostaEdit') }}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="atualizarPerguntaFrequentes">

                                                    <span wire:loading.remove wire:target="atualizarPerguntaFrequentes">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                        <span wire:loading wire:target="atualizarPerguntaFrequentes">
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

        <div class="modal fade" id="modalAdicionarPerguntasFrequentes" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                        <h4 class="smaller">
                            NOVA PERGUNTA FREQUÊNTES
                        </h4>

                    </div>

                    <div class="modal-body">
                        <div class="row" style="left: 0%; position: relative;">

                            <div class="col-md-12">
                                <form class="filter-form form-horizontal validation-form">
                                    <div class="second-row">
                                        <div class="tabbable">
                                            <div class="tab-content profile-edit-tab-content">
                                                <div id="dados_motivo" class="tab-pane in active">
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2" for="cliente">Pergunta<b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group col-md-12">
                                                                <input type="text" wire:model="pergunta" style="width: 100%; <?= $errors->has('pergunta') ? 'border-color: #ff9292;' : '' ?>"/>
                                                            </div>
                                                            @if ($errors->has('pergunta'))
                                                                <span class="help-block" style="color: red; font-weight: bold;position:absolute;"><strong>{{ $errors->first('pergunta') }}</strong></span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="form-group has-info">
                                                        <div class="col-md-12">
                                                            <label class="control-label bold label-select2"
                                                                   for="numeroCartao">Resposta<b class="red fa fa-question-circle"></b></label>
                                                            <div class="input-group col-md-12">
                                                                <textarea type="text" wire:model="resposta" style="width: 100%; <?= $errors->has('resposta') ? 'border-color: #ff9292;' : '' ?>">
                                                                </textarea>
                                                            </div>
                                                            @if ($errors->has('resposta'))
                                                                <span class="help-block" style="color: red; font-weight: bold;position:absolute;"><strong>{{ $errors->first('resposta') }}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix form-actions">
                                                <div class="col-md-9">
                                                    <button class="search-btn" style="border-radius: 10px"
                                                            wire:click.prevent="cadastrarPerguntaFrequentes">

                                                    <span wire:loading.remove wire:target="cadastrarPerguntaFrequentes">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                        <span wire:loading wire:target="cadastrarPerguntaFrequentes">
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
                PERGUNTAS FREQUENTE
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

                                <input type="text" wire:model="search" autofocus autocomplete="on" class="form-control search-query" placeholder="Buscar..." />
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
                                <a href="#modalAdicionarPerguntasFrequentes" data-toggle="modal" title="Adicionar nova perguntas frequentes" class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> Adicionar pergunta frequente
                                </a>

                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todas perguntas frequentes (Total:{{count($perguntasFrequentes)}})
                            </div>
                            <div>

                                <table class="tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pergunta</th>
                                        <th>Resposta</th>
                                        <th style="text-align: center;width: 70px">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($perguntasFrequentes as $key=> $perguntasFrequente)
                                        <tr>
                                            <td>{{ ++ $key }}</td>
                                            <td>{{ $perguntasFrequente->pergunta }}</td>
                                            <td>{{ $perguntasFrequente->resposta }}</td>

                                            <td style="text-align: center; width: 70px">
                                                <div class="hidden-sm hidden-xs action-buttons">
                                                    <a href="#modalUpdatePerguntaFrequentes" data-toggle="modal"
                                                       wire:click="showModalUpdatePerguntaFrequentes({{ json_encode($perguntasFrequente) }})"
                                                       class="pink"
                                                       title="Editar">
                                                        <i class="fa fa-pencil-square-o bigger-150 blue"></i>
                                                    </a>
                                                    <a wire:click="modalDel({{$perguntasFrequente->id}})" style="cursor: pointer"
                                                       class="pink"
                                                       title="deletar">
                                                        <i class="ace-icon fa fa-trash-o bigger-150 bolder danger red"></i>
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
                     <div>{{$perguntasFrequentes->links()}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
