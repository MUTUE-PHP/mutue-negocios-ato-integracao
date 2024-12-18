@section('title','Emitir cartão cliente')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            EMITIR CARTÃO CLIENTE
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
                                    <label class="control-label bold label-select2" for="empresaId">Cliente<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="clienteId" data="clienteId"
                                            style="height:35px;<?= $errors->has('empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Selecione...</option>
                                        @foreach($clientes as $cliente)
                                            <option
                                                value="{{ $cliente->id }}">{{ \Illuminate\Support\Str::upper($cliente->nome) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('clienteId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('clienteId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="nomeCliente">Saldo<b
                                            class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="number" wire:model="saldo" class="form-control"
                                               style="height: 35px; font-size: 10pt"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-money bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="nomeCliente">Data emissão<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="dataEmissao" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('dataEmissao') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('dataEmissao'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('dataEmissao') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="nomeCliente">Data validade<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="date" wire:model="dataValidade" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('dataValidade') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-calendar bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('dataValidade'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('dataValidade') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="emitirCartaoCliente">
                                <span wire:loading.remove wire:target="emitirCartaoCliente">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="emitirCartaoCliente">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="{{ route('cartaoClienteIndex') }}" class="btn btn-danger" type="reset"
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
