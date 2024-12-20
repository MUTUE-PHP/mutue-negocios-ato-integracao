@section('title','Novo banco')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOVO BANCO
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
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="nomeBanco">Nome do banco<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="banco.designacao" class="form-control" id="nomeBanco" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('banco.designacao') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('banco.designacao'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('banco.designacao') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="sigla">Sigla<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="banco.sigla" class="form-control" id="sigla" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('banco.sigla') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('banco.sigla'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('banco.sigla') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="numConta">Número da conta</label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="banco.num_conta" class="form-control" id="numConta" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('banco.num_conta') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('banco.num_conta'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('banco.num_conta') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="sigla">IBAN<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="banco.iban" class="form-control" id="sigla" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('banco.iban') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('banco.iban'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('banco.iban') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="swift">SWIFT</label>
                                    <div class="input-group">
                                        <input type="text" wire:model="banco.swift" class="form-control" id="swift" autofocus style="height: 35px; font-size: 10pt;" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="conta">Conta<b class="red fa fa-question-circle"></b></label>
                                    <select wire:model="banco.conta" class="col-md-12 select2" data="conta" id="conta"
                                            style="height:35px;<?= $errors->has('banco.conta') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="">Seleciona</option>
                                    @foreach($contas as $data)
                                            <option value="{{$data['conta']['numero']}}">{{$data['conta']['designacao']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="swift">Subconta</label>
                                    <div class="input-group">
                                        <input type="text" wire:model="banco.subconta" placeholder="43.1.1."  disabled class="form-control" id="subconta"  style="height: 35px; font-size: 10pt;" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="status_id">Status</label>
                                    <select wire:model="banco.status_id" class="col-md-12" id="status_id" style="height:35px;<?= $errors->has('banco.status_id') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="1">Ativo</option>
                                        <option value="2">Inativo</option>

                                    </select>
                                    @if ($errors->has('banco.status_id'))
                                    <span class="help-block" style="color: red; font-weight: bold">
                                        <strong>{{ $errors->first('banco.status_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="salvarBanco">
                                <span wire:loading.remove wire:target="salvarBanco">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="salvarBanco">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>

                            &nbsp; &nbsp;
                            <a href="{{ route('bancos.index') }}" class="btn btn-danger" type="reset" style="border-radius: 10px">
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
