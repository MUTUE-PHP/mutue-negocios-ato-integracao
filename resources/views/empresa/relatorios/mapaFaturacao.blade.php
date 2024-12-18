@section('title','Relatorios Gerais')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            MAPA DE FATURAÇÃO
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
                                    <label class="control-label bold label-select2" for="data_inicio">Data Inicial<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="data_inicio" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('data_inicio') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('data_inicio'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('data_inicio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="data_fim">Data Final<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="data_fim" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('data_fim') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('data_fim'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('data_fim') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="centroCusto">Selecione o
                                        Cliente<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="clienteId" data="clienteId"
                                            style="height:35px;<?= $errors->has('clienteId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
                                        @foreach($cliente as $cliente)
                                            <option
                                                value="{{ $cliente->id}}">{{ \Illuminate\Support\Str::upper($cliente->nome) }}</option>
                                        @endforeach
                                    </select>
                                    
                                    @if ($errors->has('clienteId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('clienteId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="centroCusto"> Tipo de Carga<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="tipoMercadoriaId"
                                            data="tipoMercadoriaId"
                                            style="height:35px;<?= $errors->has('tipoMercadoriaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
                                        @foreach($tipoMercadoria as $tipoMercadoria)
                                            <option
                                                value="{{ $tipoMercadoria->id}}">{{ \Illuminate\Support\Str::upper($tipoMercadoria->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipoMercadoriaId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('tipoMercadoriaId') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            
                                    <div class="col-md-3">
                                        <label class="control-label bold label-select2" for="centroCusto">Selecione Tipo de Serviço
                                            <b
                                                class="red fa fa-question-circle"></b></label>
                                        <select class="col-md-12 select2" wire:model="tipoServicoId" data="tipoServicoId"
                                                style="height:35px;<?= $errors->has('tipoServicoId') ? 'border-color: #ff9292;' : '' ?>">
                                            <option value="">TODOS</option>
                                            @foreach($tipoServicos as $tipoServicos)
                                                <option
                                                    value="{{ $tipoServicos->id}}">{{ \Illuminate\Support\Str::upper($tipoServicos->designacao) }}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if ($errors->has('tipoServicoId'))
                                            <span class="help-block"
                                                  style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                                <strong>{{ $errors->first('tipoServicoId') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="centroCusto">Tipo de Documento<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="tipoDocumentosId"
                                            data="tipoDocumentosId"
                                            style="height:35px;<?= $errors->has('tipoDocumentosId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
                                        @foreach($tipoDocumentos as $tipoDocumentos)
                                            <option
                                                value="{{ $tipoDocumentos->id}}">{{ \Illuminate\Support\Str::upper($tipoDocumentos->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipoDocumentosId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('tipoDocumentosId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="imprimirMapaFaturacao">
                                <span wire:loading.remove wire:target="imprimirMapaFaturacao">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Visualizar PDF
                                </span>
                                <span wire:loading wire:target="imprimirMapaFaturacao">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="imprimirExcelMapaFaturacao">
                        <span wire:loading.remove wire:target="imprimirExcelMapaFaturacao">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Visualizar EXCEL

                        </span>
                                <span wire:loading wire:target="imprimirExcelMapaFaturacao">
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
