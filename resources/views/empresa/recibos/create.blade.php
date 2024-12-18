@section('title','Emitir recibo')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            EMITIR RECIBO
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
                                    <input type="text" wire:model.debounce.500ms="recibo.numeracaoFactura" autofocus placeholder="buscar pela numeração da factura / código de barra" class="form-control" style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.numeracaoFactura') ? 'border-color: #ff9292;' : '' ?>" />
                                    @if ($errors->has('recibo.numeracaoFactura'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('recibo.numeracaoFactura') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome do cliente<b class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $recibo['nomeCliente'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="numeracaoFactura">Nº Factura</label>
                                    <div class="input-group">
                                        <input type="text" value="<?= $recibo['numeracaoFactura'] ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="contaCorrente">Valor Factura<b class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" value="<?= number_format($recibo['totalFatura'], 2, ',', '.') ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
{{--                                <div class="col-md-3">--}}
{{--                                    <label class="control-label bold label-select2" for="total_debito">Total à Débitar</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input type="text" value="<?=number_format($recibo['totalDebitar'], 2, ',','.') ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />--}}
{{--                                        <span class="input-group-addon" id="basic-addon1">--}}
{{--                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-3">--}}
{{--                                    <label class="control-label bold label-select2" for="total_debito">Total Débitado</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input type="text" value="<?=number_format($recibo['totalDebitado'], 2, ',','.') ?>" disabled class="form-control" style="height: 35px; font-size: 10pt" />--}}
{{--                                        <span class="input-group-addon" id="basic-addon1">--}}
{{--                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-3">--}}
{{--                                    <label class="control-label bold label-select2" for="saldoAtual">Valor Entregue<b class="red fa fa-question-circle"></b></label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input type="text" disabled step="any" value="{{ number_format($recibo['totalEntregue'], 2, ',', '.') }}" name="recibo.totalEntregue" class="form-control" id="saldoAtual" data-target="form_supply_price" style="height: 35px; font-size: 10pt;<?= $errors->has('recibo.totalEntregue') ? 'border-color: #ff9292;' : '' ?>" />--}}
{{--                                        <span class="input-group-addon" id="basic-addon1">--}}
{{--                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                    @if ($errors->has('recibo.totalEntregue'))--}}
{{--                                        <span class="help-block" style="color: red; font-weight: bold">--}}
{{--                                        <strong>{{ $errors->first('recibo.totalEntregue') }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="formaPagamentoId">Forma pagamento<b class="red fa fa-question-circle"></b></label>
                                    <div wire:ignore>
                                        <select wire:model="recibo.formaPagamentoId" id="formaPagamentoId" class="col-md-12" style="height:35px;">
                                            @foreach($formaPagamentos as $formaPagamento)
                                                <option value="{{ $formaPagamento->id }}"  @if($formaPagamento->id == $recibo['formaPagamentoId']) selected @endif>{{ $formaPagamento->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="numeracaoFactura">Data Pagamento</label>
                                    <div class="input-group">
                                        <input type="date" value="<?= $recibo['dataOperacao'] ?>" wire:model="recibo.dataOperacao"  max="{{$limiteDate}}"   class="form-control disabled" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="numeracaoFactura">Nº de Operação Bancária <b class="{{ $isDisabled== 1 ? '' : 'red fa fa-question-circle' }}"></b> </label>
                                    <div class="input-group">
                                        <input type="text"  wire:model="recibo.numeroOperacaoBancaria"  {{$isDisabled== 1 ? 'disabled' : '' }}  class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('recibo.numeroOperacaoBancaria'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                    <strong>{{ $errors->first('recibo.numeroOperacaoBancaria') }}</strong>
                                </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">



                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="numeracaoFactura">Anexo <b class="{{ $isDisabled== 1 ? '' : 'red fa fa-question-circle' }}"></b></label>
                                    <div class="input-group">
                                        <input type="file"  wire:model="recibo.comprovativoBancario" {{$isDisabled== 1 ? 'disabled' : '' }}  class="form-control" style="height: 35px; font-size: 10pt" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('recibo.comprovativoBancario'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                    <strong>{{ $errors->first('recibo.comprovativoBancario') }}</strong>
                                </span>
                                @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">

                                    <label class="control-label bold label-select2" for="observacao">Observação</label>
                                    <div class="input-group">
                                        <textarea wire:model="recibo.observacao" id="observacao" cols="400" rows="4" class="form-control" style="font-size: 16px; z-index: 1;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="emitirRecibo">
                                <span wire:loading.remove wire:target="emitirRecibo">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="emitirRecibo">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="{{ route('recibo.index') }}" class="btn btn-danger" type="reset" style="border-radius: 10px">
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
