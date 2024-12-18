@section('title','Atualizar estoque do produto')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            ATUALIZAR PRODUTO NO ESTOQUE
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
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="centroCustoId">Centro de
                                        custo<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="atualizacaoStock.centroCustoId"
                                            data="centroCustoId"
                                            style="height:35px;<?= $errors->has('empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                        @foreach($centrosCusto as $centroCusto)
                                            <option
                                                value="{{ $centroCusto->id }}" <?= $atualizacaoStock['centroCustoId'] == $centroCusto->id ? 'selected' : '' ?>>{{ \Illuminate\Support\Str::upper($centroCusto->nome) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('atualizacaoStock.centroCustoId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('atualizacaoStock.centroCustoId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="armazemId">Armazem<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="atualizacaoStock.armazemId" data="armazemId"
                                            style="height:35px;<?= $errors->has('empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                        @foreach($armazens as $armazem)
                                            <option
                                                value="{{ $armazem->id }}" <?= $atualizacaoStock['armazemId'] == $armazem->id?'selected':''?>>{{ \Illuminate\Support\Str::upper($armazem->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('atualizacaoStock.armazemId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('atualizacaoStock.armazemId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="produtoId">Seleciona o
                                        produto<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="atualizacaoStock.produtoId"
                                            data="produtoId"
                                            style="height:35px;<?= $errors->has('empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option valeu="">Nenhum produto</option>
                                        @foreach($existencias as $existencia)
                                            <option
                                                value="{{ $existencia->produto_id }}">{{ \Illuminate\Support\Str::upper($existencia->produto->designacao) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('atualizacaoStock.produtoId'))
                                        <span class="help-block"
                                              style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('atualizacaoStock.produtoId') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">

                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="quantidadeAnterior">Existência atual<b
                                            class="red fa fa-question-"></b></label>
                                    <div class="input-group">
                                        <input type="text" disabled value="{{ number_format($atualizacaoStock['quantidadeAnterior'], 1, ',', '.') }}" class="form-control"
                                               style="height: 35px; font-size: 10pt"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-number bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="quantidadeNova">Nova existência<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="number" wire:model="atualizacaoStock.quantidadeNova" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('atualizacaoStock.quantidadeNova') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-number bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>

                                    </div>
                                    @if ($errors->has('atualizacaoStock.quantidadeNova'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('atualizacaoStock.quantidadeNova') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="descricao">Observação</label>
                                    <div class="input-group">
                                        <textarea wire:model="atualizacaoStock.descricao" id="descricao" cols="200" rows="2" class="form-control" style="font-size: 16px; z-index: 1;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="atualizarEstoque">
                                <span wire:loading.remove wire:target="atualizarEstoque">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Atualizar
                                </span>
                                <span wire:loading wire:target="atualizarEstoque">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>
                            <a href="{{ route('atualizarStockIndex') }}" class="btn btn-danger" type="reset"
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
