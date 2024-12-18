@php use Illuminate\Support\Str; @endphp
@section('title','Fretes por comunas')

<div>
    <div class="row">
        <!-- VER DETALHES  -->
        <div class="page-header" style="left: 0.5%; position: relative">
            <h1>
                FRETES DE ENTREGA
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Listagem
                </small>
            </h1>
        </div>

        <div class="col-md-12">


            <div class>
                <div class="row">
                    <form id="adimitirCandidatos" method="POST" action>
                        <input type="hidden" name="_token" value/>

                        <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
                            <div class="clearfix">
                                <a href="{{ route('comunasFrete.create') }}" title="nova comuna"
                                   class="btn btn-success widget-box widget-color-blue" id="botoes">
                                    <i class="fa icofont-plus-circle"></i> adicionar frete
                                </a>
                                <a title="Imprimir fretes" wire:click.prevent="imprimirFretes"
                                   class="btn btn-primary widget-box widget-color-blue" id="botoes">
                                    <span wire:loading wire:target="imprimirFretes" class="loading"></span>
                                    <i class="fa fa-print text-default"></i> Imprimir
                                </a>
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div class="table-header widget-header">
                                Todos fretes do sistema (Total:{{count($comunasFrete)}})
                            </div>

                            <!-- div.dataTables_borderWrap -->
                            <div>
                                <table class=" tabela1 table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Comuna</th>
                                        <th>Município</th>
                                        <th>Província</th>
                                        <th style="text-align: right">Valor entrega</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comunasFrete as $comuna)
                                        <tr>
                                            <td>{{ $comuna->id }}</td>
                                            <td>{{ $comuna->designacao }}</td>
                                            <td>{{ Str::title($comuna->municipio->designacao) }}</td>
                                            <td>{{ Str::title($comuna->municipio->provincia->designacao) }}</td>
                                            <td style="text-align: right">{{ number_format($comuna->valor_entrega, 2, ',', '.') }}</td>
                                            <td style="text-align: center">
                                                <span class="label label-sm <?= $comuna->statusId == 1 ? 'label-success' : 'label-warning' ?>" style="border-radius: 20px;">{{ $comuna->statusId == 1?'Ativo':'Desativo' }}</span>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="hidden-sm hidden-xs action-buttons">
                                                    <a class="pink" title="Editar este registo" href="{{ route('comunasFrete.update', $comuna->id) }}">
                                                        <i class="ace-icon fa fa-pencil bigger-150 bolder success text-success"></i>
                                                    </a>
{{--                                                    <a title="Eliminar este Registro" style="cursor:pointer;"--}}
{{--                                                       wire:click="modalDel({{json_encode($comuna->id)}})">--}}
{{--                                                        <i class="ace-icon fa fa-trash-o bigger-150 bolder danger red"></i>--}}
{{--                                                    </a>--}}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $comunasFrete->links() }}
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
