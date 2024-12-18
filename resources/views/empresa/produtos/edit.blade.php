@php use Illuminate\Support\Str; @endphp
@section('title','Atualizar serviço')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            ATUALIZAR SERVIÇO
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
                                    <label class="control-label bold label-select2" for="nomeCliente">Nome<b
                                            class="red fa fa-question-circle"></b></label>
                                    <div class="input-group">
                                        <input type="text" autofocus wire:model="produto.designacao"
                                               class="form-control" id="nomeProduto"
                                               style="height: 35px; font-size: 10pt;<?= $errors->has('produto.designacao') ? 'border-color: #ff9292;' : '' ?>"/>
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="ace-icon fa fa-info bigger-150 text-info"
                                               data-target="form_supply_price_smartprice"></i>
                                        </span>
                                    </div>
                                    @if ($errors->has('produto.designacao'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.designacao') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="tipoServicoId">Tipos de
                                        serviços<b
                                            class="red fa fa-question-circle"></b></label>
                                    <select wire:model="produto.tipoServicoId" data="tipoServicoId"
                                            class="col-md-12 select2"
                                            id="tipoServicoId"
                                            style="height:35px;">
                                        <option value="">Nenhum</option>
                                        @foreach($tiposServicos as $servico)
                                            <option
                                                value="{{ $servico->id }}" <?= $servico->id == $produto['tipoServicoId'] ? 'selected' : '' ?>>{{ $servico->designacao }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('produto.tipoServicoId'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.tipoServicoId') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="status_id">Status</label>
                                    <select wire:model="produto.status_id" data="status_id" class="col-md-12 select2"
                                            id="status_id"
                                            style="height:35px;<?= $errors->has('produto.status_id') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="1" <?= $produto['status_id'] == 1 ? 'selected' : '' ?>>Activo
                                        </option>
                                        <option value="2" <?= $produto['status_id'] == 2 ? 'selected' : '' ?>>Inativo
                                        </option>
                                    </select>
                                    @if ($errors->has('produto.status_id'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.status_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-info bold" style="left: 0%; position: relative">
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2" for="stocavel">Produto
                                        estocavel?</label>
                                    <select wire:model="produto.stocavel" data="stocavel" class="col-md-12 select2"
                                            id="stocavel"
                                            style="height:35px;<?= $errors->has('produto.stocavel') ? 'border-color: #ff9292;' : '' ?>">
                                        <option value="Sim" <?= $produto['stocavel'] == 'Sim' ? 'selected' : '' ?>>Sim
                                        </option>
                                        <option value="Não" <?= $produto['stocavel'] == 'Não' ? 'selected' : '' ?>>Não
                                        </option>
                                    </select>
                                    @if ($errors->has('produto.stocavel'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.stocavel') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label bold label-select2"
                                           for="codigo_taxa">Imposto(IVA)</label>
                                    <select wire:model="produto.codigo_taxa" data="codigo_taxa"
                                            class="col-md-12 select2" id="codigo_taxa"
                                            style="height:35px;<?= $errors->has('produto.codigo_taxa') ? 'border-color: #ff9292;' : '' ?>">
                                        @foreach($taxasIva as $taxa)
                                            <option value="{{$taxa->codigo}}" <?= $taxa->codigo == $produto['codigo_taxa'] ? 'selected' : '' ?>>{{$taxa->descricao}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('produto.codigo_taxa'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.codigo_taxa') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label bold label-select2" for="motivo_isencao_id">Motivo de
                                        Isenção</label>
                                    <select wire:model="produto.motivo_isencao_id" data="motivo_isencao_id"
                                            class="col-md-12 select2"
                                            id="motivo_isencao_id"
                                            style="height:35px;<?= $errors->has('produto.motivo_isencao_id') ? 'border-color: #ff9292;' : '' ?>">
                                        @foreach($motivosIsencao as $motivo)
                                            <option value="{{$motivo->codigo}}" <?= $motivo->codigo == $produto['motivo_isencao_id'] ? 'selected' : '' ?>>{{$motivo->descricao}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('produto.codigo_taxa'))
                                        <span class="help-block"
                                              style="color: red; font-weight: bold;position:absolute;">
                                        <strong>{{ $errors->first('produto.codigo_taxa') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <a class="search-btn" type="submit" style="border-radius: 10px"
                               wire:click.prevent="update">
                                <span wire:loading.remove wire:target="update" wire:keydown.enter="preventEnter">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Salvar
                                </span>
                                <span wire:loading wire:target="update">
                                    <span class="loading"></span>
                                    Aguarde...</span>
                            </a>
                            &nbsp; &nbsp;
                            <a href="{{ route('produtos.index') }}" class="btn btn-danger" type="reset"
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

@push('scripts')
    <script>
        $(document).ready(function () {

            $('.select3').select2(); // Inicializa o Select2 em todos os elementos com classe "select2
            $(document.body).on("change", ".select3", function (e) {
                const data = {
                    'atributo': e.target.getAttribute('data'),
                    'valor': e.target.value
                }
                livewire.emit('selectedItem', data)
            });
            window.livewire.on('select2', () => {
                $('.select3').select2(); // Inicializa o Select2 em todos os elementos com classe "select2"

            });
        });
    </script>

@endpush
