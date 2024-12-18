@section('title','Resetar senha do cliente')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            RESETAR SENHA DO CLIENTE
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
                @csrf
                <div class="second-row">
                    <div class="tabbable">
                        <div class="tab-content profile-edit-tab-content">


                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="form-group has-info bold" style="left: 0%; position: relative">
                                    <div class="col-md-6">
                                        <label class="control-label bold label-select2" for="empresaId">Cliente<b class="red fa fa-question-circle"></b></label>
                                        <select class="col-md-12 select2" wire:model="empresaId" data="empresaId" style="height:35px;<?= $errors->has('empresaId') ? 'border-color: #ff9292;' : '' ?>">
                                            <option value="">Informe a licença</option>
                                            @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ \Illuminate\Support\Str::upper($cliente->nome) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('empresaId'))
                                        <span class="help-block" style="color: red;position: absolute;margin-top: -2px;font-size: 12px;">
                                            <strong>{{ $errors->first('empresaId') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label bold label-select2" for="newSenha">Nova senha<b class="red fa fa-question-circle"></b></label>
                                        <div class="input-group">
                                            <input type="text" wire:model="newSenha" class="form-control" id="newSenha" data-target="form_supply_price" style="height: 35px; font-size: 10pt;<?= $errors->has('utilizador.username') ? 'border-color: #ff9292;' : '' ?>" />
                                            <span class="input-group-addon" id="basic-addon1">
                                                <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('newSenha'))
                                        <span class="help-block" style="color: red; font-weight: bold">
                                            <strong>{{ $errors->first('newSenha') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="atualizarSenha">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    <span wire:loading.remove wire:target="atualizarSenha">
                                        Salvar
                                    </span>
                                    <span wire:loading wire:target="atualizarSenha">
                                        <span class="loading"></span>

                                        Aguarde...</span>
                                </button>

                                &nbsp; &nbsp;
                                <a class="btn btn-danger" type="reset" href="/admin" style="border-radius: 10px">
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
