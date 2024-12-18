@section('title','Anulação de recibos')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            ANULAÇÃO DE RECIBOS
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
                                    <label class="control-label bold label-select2" for="numeracaoRecibo">Buscar recibo<b class="red fa fa-question-circle"></b></label>
                                    <input type="text" wire:model.debounce.500ms="numeracaoRecibo" autofocus placeholder="buscar pela numeração do recibo" class="form-control" style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.numeracaoFactura') ? 'border-color: #ff9292;' : '' ?>" />
                                    @if ($errors->has('recibo.numeracaoRecibo'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('recibo.numeracaoRecibo') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome do cliente<b class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $recibo['nomeCliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nifCliente">NIF</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $recibo['nifCliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label bold label-select2" for="numFatura">Nº Factura</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $recibo['numFatura'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label bold label-select2" for="totalFatura">Total Factura</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= number_format($recibo['totalFatura'], 2, ',', '.') ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="nifCliente">Observação</label>
                                    <div>
                                        <input type="text" wire:model="recibo.descricao" class="form-control col-md-12" style="height: 35px; font-size: 10pt" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="anularRecibo">
                                <span wire:loading.remove wire:target="anularRecibo">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Finalizar
                                </span>
                                <span wire:loading wire:target="anularRecibo">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="/empresa/documentos/anulado/recibos" class="btn btn-danger" type="reset" style="border-radius: 10px">
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
