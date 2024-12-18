@section('title','Fecho de caixa')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            FECHO DE CAIXA
        </h1>
    </div>
    <div class="row" style="left: 0%; position: relative;">

        <div class="col-md-12">
            <form class="filter-form form-horizontal validation-form">
                <div class="second-row">

                    <div class="tabbable">
                        <div class="tab-content profile-edit-tab-content">
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2"
                                        for="centroCusto">operadores<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12 select2" wire:model="operadorSelecionado"
                                        data="operadores"
                                        style="height:35px;<?= $errors->has('operadorSelecionado') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Selecione o Operador</option>
                                        @foreach($operadores as $operador)
                                        <option value="{{ $operador->id}}">
                                            {{ \Illuminate\Support\Str::upper($operador->name) }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('operadorSelecionado'))
                                    <span class="help-block"
                                        style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                        <strong>{{ $errors->first('operadorSelecionado') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="">Data Inicial<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="datetime-local" wire:model="data_inicio"
                                            class="form-control"
                                            style="height: 35px; font-size: 10pt;<?= $errors->has('data_inicio') ? 'border-color: #ff9292;' : '' ?>" />
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
                                    <label class="control-label bold label-select2" for="data_fim">Data
                                        Final<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="datetime-local" wire:model="data_fim"
                                            class="form-control"
                                            style="height: 35px; font-size: 10pt;<?= $errors->has('') ? 'border-color: #ff9292;' : '' ?>" />
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
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                wire:click.prevent="imprimirFechoCaixa">
                                <span wire:loading.remove wire:target="imprimirFechoCaixa">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Visualizar PDF
                                </span>
                                <span wire:loading wire:target="imprimirFechoCaixa">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="{{ route('funcionario/home') }}" class="btn btn-danger"
                                type="reset" style="border-radius: 10px">
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
    </div>
</div>
