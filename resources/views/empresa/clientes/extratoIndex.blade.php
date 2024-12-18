@section('title','Extrato do cliente')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            EXTRATO DO CLIENTE
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
                                    <label class="control-label bold label-select2" for="clienteId">CLIENTE<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="extrato.clienteId" data="clienteId"
                                            style="height:35px;<?= $errors->has('centroCustoId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Seleciona o cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option
                                                value="{{$cliente->id}}">{{ \Illuminate\Support\Str::upper($cliente->nome) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('extrato.clienteId'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold">
                                            <strong>{{ $errors->first('extrato.clienteId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="tipoDocumentoId">TIPOS
                                        DOCUMENTO<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="extrato.tipoDocumentoId"
                                            data="tipoDocumentoId"
                                            style="height:35px;<?= $errors->has('venda_online') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">TODOS</option>
{{--                                        @foreach($tipoDocumentos as $documento)--}}
{{--                                            <option value="{{ $documento->id }}">{{ $documento->designacao }}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="dataInicio">Data Inicial<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="datetime-local" wire:model="extrato.dataInicio"
                                               class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('extrato.dataInicio') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('extrato.dataInicio'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('extrato.dataInicio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="dataFinal">Data Final<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="datetime-local"
                                               {{ $todoPeriodo?"disable":"" }} wire:model="extrato.dataFinal"
                                               class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('extrato.dataFinal') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('extrato.dataFinal'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('extrato.dataFinal') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="imprimirExtratoCliente">
                                <span wire:loading.remove wire:target="imprimirExtratoCliente">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    IMPRIMIR
                                </span>
                                <span wire:loading wire:target="imprimirExtratoCliente">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="{{ route('extratoCliente') }}" class="btn btn-danger" type="reset"
                               style="border-radius: 10px">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
