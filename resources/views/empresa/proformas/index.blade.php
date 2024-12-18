@section('title','Converter proformas')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            CONVERTER PROFORMAS
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-warning hidden-sm hidden-xs">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                Os campos marcados com
                <span class="tooltip-target" data-toggle="tooltip" data-placement="top"><i class="fa fa-question-circle bold text-danger"></i></span>
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
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="numeracaoFactura">Buscar factura<b class="red fa fa-question-circle"></b></label>
                                    <input type="text" wire:model.debounce.500ms="numeracaoFactura" autofocus placeholder="buscar pela numeração da factura / código de barra" class="form-control" style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.numeracaoFactura') ? 'border-color: #ff9292;' : '' ?>" />
                                    @if ($errors->has('recibo.numeracaoFactura'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('recibo.numeracaoFactura') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="formaPagamentoId">Forma pagamento<b class="red fa fa-question-circle"></b></label>
                                    <div>
                                        <select wire:model="proforma.formaPagamentoId" id="formaPagamentoId" class="col-md-12" style="height:35px;">
                                            @foreach($formaPagamentos as $formaPagamento)
                                                <option value="{{ $formaPagamento->id }}"  @if($formaPagamento->id == $proforma['formaPagamentoId']) selected @endif>{{ $formaPagamento->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome do cliente<b class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $proforma['nomeCliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label bold label-select2" for="nifCliente">NIF</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $proforma['nifCliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="nifCliente">Proprietário/Companhia Aérea</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $proforma['nomeProprietario'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR ILIQUIDO(AOA) :
                                                <span>{{ number_format($proforma['valorIliquido'], 2, ',', '.') }}</span>
                                            </h8>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                IVA(%) :
                                                <span>{{ number_format($proforma['taxaIva'], 2,',','.') }}%</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR DO IMPOSTO(AOA) :
                                                <span>{{ number_format($proforma['valorImposto'], 1,',','.') }}Kz</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                RETENÇÃO(%) :
                                                <span>{{ number_format($proforma['taxaRetencao'], 2,',','.') }}%</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR DA RETENÇÃO(AOA) :
                                                <span>{{ number_format($proforma['valorRetencao'], 2,',','.') }}Kz</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                TOTAL(AOA) :
                                                <span><strong>{{ number_format($proforma['total'], 2,',','.') }}Kz</strong></span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                TAXA DE CÂMBIO(AOA/{{$proforma['moeda'] ??'?'}}) :
                                                <span>{{ number_format($proforma['cambioDia'], 2,',','.') }}</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                CONTRAVALOR({{$proforma['moeda'] ??'?'}}) :
                                                <span><strong>${{ number_format($proforma['contraValor'], 2,',','.') }}</strong></span>
                                            </h8>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="converterProforma">
                                <span wire:loading.remove wire:target="converterProforma">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Finalizar
                                </span>
                                <span wire:loading wire:target="converterProforma">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="/empresa/home" class="btn btn-danger" type="reset" style="border-radius: 10px">
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
