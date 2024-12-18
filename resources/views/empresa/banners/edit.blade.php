@section('title','Editar banner')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            EDITAR BANNER
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
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="texto" wire:model="nome" autofocus class="form-control"
                                               style="height: 35px; font-size: 10pt; <?= $errors->has('nome') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('nome'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="nomeCliente">Descrição<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="texto" wire:model="descricao" class="form-control"
                                               style="height: 35px; font-size: 10pt; <?= $errors->has('descricao') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('descricao'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('descricao') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold label-select2" for="status_id">Status</label>
                                    <select wire:model="status_id" data="status_id" class="col-md-12 select2"
                                            id="status_id"
                                            style="height:35px;<?= $errors->has('status_id') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="1" <?= $status_id == 1 ? 'selected' : ''?>>Activo</option>
                                        <option value="2" <?= $status_id == 2 ? 'selected' : ''?>>Inativo</option>
                                    </select>
                                    @if ($errors->has('status_id'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('status_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    <label class="control-label bold label-select2" for="nomeCliente">Imagem (jpeg, png, jpg)</label>
                                    <div class="input-group">
                                        <input type="file" wire:model="imagemNovo" class="form-control"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('dataEmissao') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-file bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('imagemNovo'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('imagemNovo') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <img src="{{ env('APP_URL'). $imagens }}" style="width: 300px" >
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px"
                                    wire:click.prevent="updateBanner">
                                <span wire:loading.remove wire:target="updateBanner">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="updateBanner">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            <a href="{{ route('anunciosBanner.index') }}" class="btn btn-danger" type="reset"
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
