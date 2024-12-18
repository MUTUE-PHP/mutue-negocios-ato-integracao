@php use Carbon\Carbon;use Illuminate\Support\Str; @endphp
@section('title','Bônus do cartão cliente')
<div class="row">
    <div class="modal fade" id="modalEditarBonus" wire:ignore>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close red bolder" data-dismiss="modal">×</button>
                    <h4 class="smaller">
                        <i class="ace-icon fa fa-plus-circle bigger-150 blue"></i> ATUALIZAR O BONUS(%) DE COMPRA
                    </h4>
                </div>

                <div class="modal-body">
                    <div class="row" style="left: 0%; position: relative;">

                        <div class="col-md-12">
                            <form class="filter-form form-horizontal validation-form">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <span>{{ $errors->all()[0] }}</span>
                                    </div>
                                @endif
                                <div class="second-row">
                                    <div class="tabbable">
                                        <div class="tab-content profile-edit-tab-content">
                                            <div id="dados_motivo" class="tab-pane in active">
                                                <div class="form-group has-info">
                                                    <div class="col-md-12">
                                                        <label class="control-label bold label-select2" for="sequencia">Bônus(%) por compra<b
                                                                class="red fa fa-question-circle"></b></label>
                                                        <div>
                                                            <input type="number" wire:model="bonus"
                                                                   id="sequencia" class="col-md-12 col-xs-12 col-sm-4"/>
                                                        </div>
                                                        @if ($errors->has('bonus'))
                                                            <span class="help-block" style="color: red; font-weight: bold"><strong>{{ $errors->first('bonus') }}</strong></span>
                                                        @endif
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-9">
                                                <button class="search-btn" style="border-radius: 10px"
                                                        wire:click.prevent="atualizarBonusCartaoCliente">

                                                    <span wire:loading.remove wire:target="atualizarBonusCartaoCliente">
                                                        <i class="ace-icon fa fa-print bigger-110"></i>
                                                        SALVAR
                                                    </span>
                                                    <span wire:loading wire:target="atualizarBonusCartaoCliente">
                                                        <span class="loading"></span>
                                                        Aguarde...</span>
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            BÔNUS(%) CARTÃO CLIENTES
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Listagem
            </small>
        </h1>
    </div>

    <div class="col-md-12">
        <div class>
            <form class="form-search" method="get" action>
                <div class="row">
                    <div class>
                        <div class="input-group input-group-sm" style="margin-bottom: 10px">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-search"></i>
                            </span>

                            <input type="text" wire:model="search" autofocus autocomplete="on"
                                   class="form-control search-query"
                                   placeholder=""/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-lg upload">
                                    <span class="ace-icon fa fa-search icon-on-right bigger-130"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class>
            <div class="row">
                <form id="adimitirCandidatos" method="POST" action>
                    <input type="hidden" name="_token" value/>

                    <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                        <div class="clearfix">
                            @if(count($bonusCartaoCliente) <= 0)
                            <a href="#modalEditarBonus" data-toggle="modal" wire:click="modalAddBonus" title="emitir novo recibo" class="btn btn-success widget-box widget-color-blue" id="botoes">
                                <i class="fa icofont-plus-circle"></i> Adicionar bônus(%)
                            </a>
                            @endif
                            <div class="pull-right tableTools-container"></div>
                        </div>
                        <div class="table-header widget-header">
                            Bônus cartão clientes do sistema (Total:{{count($bonusCartaoCliente)}})
                        </div>

                        <!-- div.dataTables_borderWrap -->
                        <div>
                            <table class="tabela1 table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Bônus</th>
                                    <th>Operador</th>
                                    <th>Emitido</th>
                                    <th style="text-align: center">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bonusCartaoCliente as $bonuCliente)
                                    <tr>
                                        <td>{{ number_format($bonuCliente->bonus,1, ',', '.') }}%</td>
                                        <td>{{  Str::upper($bonuCliente->user->name) }}</td>
                                        <td>{{ date('d/m/Y H:i:s', strtotime($bonuCliente->created_at)) }}</td>
                                        <td style="text-align: center">
                                            <div class="hidden-sm hidden-xs action-buttons">
                                                <a href="#modalEditarBonus" data-toggle="modal"  wire:click="modalEditaBonus({{ json_encode($bonuCliente) }})"  class="pink" title="Editar o bonus da compra"
                                                   style="cursor: pointer">
                                                    <i class="ace-icon fa fa-pencil bigger-150 bolder success text-success"></i>
                                                </a>
                                                <a  data-toggle="modal"  title="Eliminar o bonus da compra"
                                                   style="cursor:pointer;" wire:click="modalDel({{$bonuCliente}})">
                                                    <i class="ace-icon fa fa-trash-o bigger-150 bolder danger red"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
