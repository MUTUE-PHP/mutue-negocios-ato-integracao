@php use Illuminate\Support\Str; @endphp
@section('title','Nova categoria')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOVO FRETE
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
                                    <label class="control-label bold label-select2" for="municipio">Munícipio
                                        <b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" wire:model="municipio.designacao" class="form-control"
                                               id="municipio" autofocus
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('municipio.designacao') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('municipio.designacao'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('municipio.designacao') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2">Província</label>
                                    <select wire:model="municipio.provinciaId" data="provinciaId" class="col-md-12 select2"
                                            style="height:35px;<?= $errors->has('municipio.provinciaId') ? 'border-color: #ff9292;' : '' ?>">
                                        @foreach($provincias as $provincia)
                                            <option value="{{ $provincia->id }}" <?= $provincia->id == $municipio['provinciaId']?'selected':''?>>{{ Str::upper($provincia->designacao) }}</option>
                                        @endforeach

                                    </select>
                                    @if ($errors->has('municipio.provinciaId'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('municipio.provinciaId') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="valorEntrega">Valor de entrega
                                        <b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="number" wire:model="municipio.valorEntrega" class="form-control"
                                               id="valorEntrega" autofocus
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('municipio.valorEntrega') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-money bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('municipio.valorEntrega'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('municipio.valorEntrega') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="statusId">Status</label>
                                    <select wire:model="municipio.statusId" data="statusId"
                                            class="col-md-12 select2"
                                            id="statusId"
                                            style="height:35px;<?= $errors->has('municipio.statusId') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="1" <?= $municipio['statusId'] == 1?'selected':''?>>ATIVO</option>
                                        <option value="2"  <?= $municipio['statusId'] == 2?'selected':''?>>DESATIVO</option>

                                    </select>
                                    @if ($errors->has('municipio.statusId'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('municipio.statusId') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="updateFrete">
                                <span wire:loading.remove wire:target="updateFrete">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="updateFrete">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            &nbsp; &nbsp;
                            <a href="{{ route('municipiosFrete.index') }}" class="btn btn-danger" type="reset"
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
