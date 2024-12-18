@section('title', 'Novo utilizador')
<div class="row">

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"  wire:ignore aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle"> ATENÇÃO !</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-warning hidden-sm hidden-xs">
                @if ($qtyUser >= 2)
                <p>Para adicionar mais um utilizador será cobrado 20% do valor da licença atual. Caso deseja
                    continuar será gerado uma factura para pagamento</p>
                @endif
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary"data-dismiss="modal">Não</button>
              <button class="btn btn-primary" wire:click.prevent="salvarUtilizador">
                <span wire:loading.remove wire:target="salvarUtilizador" >
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Salvar
                </span>
                <span wire:loading wire:target="salvarUtilizador">
                    <span class="loading"></span>
                    Aguarde...

                </span>
            </button>
            </div>

          </div>
        </div>
      </div>

    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            NOVO UTILIZADOR
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-warning hidden-sm hidden-xs">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <p>Os campos marcados com<span class="tooltip-target" data-toggle="tooltip" data-placement="top"><i class="fa fa-question-circle bold text-danger"></i></span>
                    são de preenchimento obrigatório.</p>
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
                                    <label class="control-label bold label-select2" for="nomeUtilizador">Nome<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="user.name" class="form-control" id="nomeUtilizador" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('user.name') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('user.name'))
                                    <span class="help-block" style="    color: red;
    position: absolute;
    margin-top: -2px;
    font-size: 12px;">
                                        <strong>{{ $errors->first('user.name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="sigla">Username</label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="user.username" class="form-control" id="sigla" style="height: 35px; font-size: 10pt;" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>

                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="numConta">E-mail<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="user.email" class="form-control" id="numConta" style="height: 35px; font-size: 10pt;<?= $errors->has('user.email') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('user.email'))
                                    <span class="help-block" style="    color: red;
    position: absolute;
    margin-top: -2px;
    font-size: 12px;">
                                        <strong>{{ $errors->first('user.email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="telefone">Telefone<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="user.telefone" maxlength="9" class="form-control" id="telefone" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('user.telefone') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('user.telefone'))
                                    <span class="help-block" style="color: red;
    position: absolute;
    margin-top: -2px;
    font-size: 12px;">
                                        <strong>{{ $errors->first('user.telefone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="password">Senha<b class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="password" wire:model="user.password" maxlength="9" class="form-control" id="password" autofocus style="height: 35px; font-size: 10pt;<?= $errors->has('user.telefone') ? 'border-color: #ff9292;' : '' ?>" />
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info" data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('user.password'))
                                        <span class="help-block" style="color: red;
    position: absolute;
    margin-top: -2px;
    font-size: 12px;">
                                        <strong>{{ $errors->first('user.password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="status_id">Status</label>
                                    <select class="col-md-12" style="height:35px;">
                                        <option value="1">Ativo</option>
                                        <option value="2">Inativo</option>
                                    </select>
                                    @if ($errors->has('user.status_id'))
                                    <span class="help-block" style="color: red;position: absolute; margin-top: -2px;font-size: 12px;">
                                        <strong>{{ $errors->first('user.status_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="telefone">Foto</label>
                                    <div class="input-group">
                                        <input type="file" wire:model="user.foto" class="form-control" id="telefone" style="height: 35px; font-size: 10pt;" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12">
                                    @foreach($modulos as $key=> $modulo)
                                        <div>
                                            <input type="checkbox" wire:model="user.modulos.{{ $modulo->id }}" id="<?=$modulo->id?>">
                                            <label for="<?=$modulo->id?>">{{ $modulo->designacao }}</label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-12" style="left: 0%; position: relative">
                                    <label class="control-label bold label-select2">Selecione a função do usuario<b class="red fa fa-question-circle"></b></label>
                                    <select class="col-md-12" multiple id="selectMultiplo" style="height:35px;">
                                        @foreach($roles->sortBy('designacao') as $role)
                                         <option value="{{ $role->id }}">{{ $role->designacao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">

                            <button class="search-btn" type="submit" style="border-radius: 10px" wire:click.prevent="salvarUtilizador">
                                <span wire:loading.remove wire:target="salvarUtilizador">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="salvarUtilizador">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </button>&nbsp; &nbsp;
                            <a href="{{ route('users.index') }}" class="btn btn-danger" type="reset" style="border-radius: 10px">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>


