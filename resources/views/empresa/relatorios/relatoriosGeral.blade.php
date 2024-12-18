@section('title','Relatorios Gerais')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            RELATORIOS GERAIS
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-warning hidden-sm hidden-xs">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                Os campos marcados com
                <span class="tooltip-target" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-question-circle bold text-danger"></i></span>
                são de preenchimento obrigatório.
            </div>
        </div>
    </div>
    @if (Session::has('success'))
        <div class="alert alert-success alert-success col-xs-12" style="left: 0%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fa fa-check-square-o bigger-150"></i>{{ Session::get('success') }}</h5>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form class="filter-form form-horizontal validation-form" id="validation-form">
                <div class="second-row">
                    <div class="tabbable">
                        <div class="tab-content profile-edit-tab-content">
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="dataInicio">Data Inicial<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="relatorio.dataInicio" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('data_inicio') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('relatorio.dataInicio'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('relatorio.dataInicio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="dataFim">Data Final<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="relatorio.dataFim" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('data_fim') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('relatorio.dataFim'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('relatorio.dataFim') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="tipoDocumentoId">Tipos de documentos</label>
                                    <select class="col-md-12 select2" wire:model="relatorio.tipoDocumentoId" data="tipoDocumentoId"
                                            style="height:35px;<?= $errors->has('clienteId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
                                        @foreach($tiposDocumentos as $tipoDocumento)
                                            <option
                                                value="{{ $tipoDocumento->id}}">{{ \Illuminate\Support\Str::upper($tipoDocumento->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('relatorio.tipoDocumentoId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('relatorio.tipoDocumentoId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="tipoServicoId"> Tipo de serviços</label>
                                    <select class="col-md-12 select2" wire:model="relatorio.tipoServicoId"
                                            data="tipoServicoId"
                                            style="height:35px;<?= $errors->has('tipoMercadoriaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
                                        @foreach($tiposServicos as $servico)
                                            <option
                                                value="{{ $servico->id}}">{{ \Illuminate\Support\Str::upper($servico->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('relatorio.tipoServicoId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('relatorio.tipoServicoId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="imprimirRelatorioGeral">
                                <span wire:loading.remove wire:target="imprimirRelatorioGeral">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Visualizar PDF
                                </span>
                                <span wire:loading wire:target="imprimirRelatorioGeral">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="imprimirExcelRelatorioGeral">
                        <span wire:loading.remove wire:target="imprimirExcelRelatorioGeral">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Visualizar EXCEL

                        </span>
                                <span wire:loading wire:target="imprimirExcelRelatorioGeral">
                            <span class="loading"></span>
                            Aguarde...</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
