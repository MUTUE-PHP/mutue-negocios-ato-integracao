@php use Illuminate\Support\Str; @endphp
@section('title','Definir parametros')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            DEFINIR PARAMETROS
        </h1>
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
                    <div class="row" style="left: 0%; position: relative">
                        <div class="col-md-12">
                            <div class="second-row">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs padding-16">
                                        <li class="active">
                                            <a data-toggle="tab" href="#dados_user">
                                                <i class="green ace-icon fa fa fa-id-card-o bigger-125"></i>
                                                PARAMETROS DA EMPRESA
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content profile-edit-tab-content">
                                        <div id="dados_user" class="tab-pane in active">
                                            <table class="table table-bordered">
                                                <tbody>

                                                @foreach($parametros as $key=>$parametro)
                                                    <tr>
                                                        <td>{{ Str::upper($parametro['designacao']) }}</td>
                                                        <td style="width: 200px">
                                                            @if($parametro['type'] == 'number')
                                                                <div class="input-group">
                                                                    <input type="number" step="0.01"
                                                                           value="{{ $parametro['valor'] }}"
                                                                           id="valorParametroId" data="valorParametro"
                                                                           class="form-control"
                                                                           style="height: 35px; font-size: 10pt;"/>
                                                                    <span class="input-group-addon" id="basic-addon1">
                                                                    <i class="ace-icon fa fa-info bigger-150 text-info"
                                                                       data-target="form_supply_price_smartprice"></i>
                                                                </span>
                                                                </div>
                                                            @endif
                                                            @if($parametro['type'] == 'select')
                                                                <select class="col-md-12 select2" data="valorParametro"
                                                                        style="height:35px;">
                                                                    @foreach ($parametro['valorSelects'] as $select)
                                                                        <option
                                                                            value="{{ $select }}" <?= $select == $parametro['valor'] ? 'selected' : '' ?>>{{ Str::upper($select) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                            @if($parametro['type'] == 'time')
                                                                <div class="input-group">
                                                                    <input type="time" value="{{ $parametro['valor'] }}"
                                                                           id="valorParametroId" data="valorParametro"
                                                                           class="form-control"
                                                                           style="height: 35px; font-size: 10pt;"/>
                                                                    <span class="input-group-addon" id="basic-addon1">
                                                                    <i class="ace-icon fa fa-info bigger-150 text-info"
                                                                       data-target="form_supply_price_smartprice"></i>
                                                                </span>
                                                                </div>
                                                            @endif
                                                                @if($parametro['type'] == 'text')
                                                                    <div class="input-group">
                                                                        <input type="text" value="{{ $parametro['valor'] }}"
                                                                               id="valorParametroId" data="valorParametro"
                                                                               class="form-control"
                                                                               style="height: 35px; font-size: 10pt;"/>
                                                                        <span class="input-group-addon" id="basic-addon1">
                                                                    <i class="ace-icon fa fa-info bigger-150 text-info"
                                                                       data-target="form_supply_price_smartprice"></i>
                                                                </span>
                                                                    </div>
                                                                @endif
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary widget-box widget-color-blue"
                                                               id="botoes"
                                                               style="border-radius: 10px"
                                                               wire:click.prevent="atualizarParametro({{$parametro->id}})">
                                                                <span wire:loading.remove
                                                                      wire:target="atualizarParametro({{$parametro->id}})">
                                                                    ATUALIZAR
                                                                </span>
                                                                <span wire:loading
                                                                      wire:target="atualizarParametro({{ $parametro->id }})">
                                                                    <span class="loading"></span>Aguarde...</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

