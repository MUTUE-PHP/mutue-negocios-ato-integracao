@section('title','Anulação de facturas')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOTA CRÉDITO - ANULAÇÃO DE FATURAS - SERVIÇO COMERCIAL
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
                                    <input type="text" value="<?= $notaCredito['numeracaoFactura']?>" disabled class="form-control" style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.numeracaoFactura') ? 'border-color: #ff9292;' : '' ?>" />
                                    @if ($errors->has('recibo.numeracaoFactura'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('recibo.numeracaoFactura') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome do cliente<b class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $notaCredito['nome_do_cliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nifCliente">NIF</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $notaCredito['nif_cliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nomeProprietario">Proprietário/Companhia Aérea</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $notaCredito['nomeProprietario'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="descricao">Observação<b class="red fa fa-question-circle"></b></label>
                                    <div>
                                        <input type="text" wire:model="observacao" class="form-control col-md-12" style="height: 35px; font-size: 10pt;<?= $errors->has('observacao') ? 'border-color: #ff9292;' : '' ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR ILIQUIDO(AOA) :
                                                <span>{{ number_format($notaCredito['valorIliquido'], 2, ',', '.') }}</span>
                                            </h8>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                IVA(%) :
                                                <span>{{ number_format($notaCredito['taxaIva'], 2,',','.') }}%</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR DO IMPOSTO(AOA) :
                                                <span>{{ number_format($notaCredito['valorImposto'], 1,',','.') }}Kz</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                RETENÇÃO(%) :
                                                <span>{{ number_format($notaCredito['taxaRetencao'], 2,',','.') }}%</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                VALOR DA RETENÇÃO(AOA) :
                                                <span>{{ number_format($notaCredito['valorRetencao'], 2,',','.') }}Kz</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                TOTAL(AOA) :
                                                <span><strong>{{ number_format($notaCredito['total'], 2,',','.') }}Kz</strong></span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                TAXA DE CÂMBIO(AOA/{{$notaCredito['moeda'] ??'?'}}) :
                                                <span>{{ number_format($notaCredito['cambioDia'], 2,',','.') }}</span>
                                            </h8>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 pull-right">
                                            <h8 class="pull-right">
                                                CONTRAVALOR({{$notaCredito['moeda'] ??'?'}}) :
                                                <span><strong>${{ number_format($notaCredito['contraValor'], 2,',','.') }}</strong></span>
                                            </h8>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="anularFatura">
                                <span wire:loading.remove wire:target="anularFatura">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Finalizar
                                </span>
                                <span wire:loading wire:target="anularFatura">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="/empresa/documentos/anulado/faturas" class="btn btn-danger" type="reset" style="border-radius: 10px">
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
