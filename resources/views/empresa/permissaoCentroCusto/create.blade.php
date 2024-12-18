@php use Illuminate\Support\Str; @endphp
@section('title','Permissões de acesso ao centro de custo')
<div class="row">
    <div class="space-6"></div>
    <div class="page-header" style="left: 0.5%; position: relative">
        <h1>
            CENTROS DE CUSTO / PERMISSÕES - {{ Str::upper($user->name) }}
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
                                                CENTROS DE CUSTO
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content profile-edit-tab-content">
                                        <div id="dados_user" class="tab-pane in active">
                                            <table class="table table-bordered">
                                                <tbody>

                                                @foreach($centrosCusto as $key=>$centroCusto)
                                                    <tr>
                                                        <td>{{ Str::upper($centroCusto['nome']) }}</td>
                                                        <td>
                                                            <input type="checkbox" wire:model="idsCentrosCustoUser"  style="min-width: 20px;min-height: 20px;cursor: pointer;margin-right: 5px;" wire:click="checkPermissaoPorUsuario({{$centroCusto->id}})" value="{{$centroCusto['id']}}" @if(in_array($centroCusto['id'],$idsCentrosCustoUser )) checked @endif>
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

