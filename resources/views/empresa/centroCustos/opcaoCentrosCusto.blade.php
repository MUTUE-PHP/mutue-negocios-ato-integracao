@section('title','Centros de Custo')
<div>

    <div class="alert alert-block alert-danger">
    
     <center>
        <strong>
            <h5>Por favor, escolha o centro de custo no qual você deseja realizar suas atividades</h5>
        </strong>
    </center>
    </div>
    <div class="row">
        @foreach($centrosCusto as $centroCusto)
            <div class="col-xs-6 col-sm-4 col-md-3">
                <a href="#" wire:click="selecionarCentroCusto({{ json_encode($centroCusto) }})">
                    <div class="thumbnail search-thumbnail" style="height: 250px;"> <!-- Defina a altura desejada aqui -->
                        <span class="search-promotion label label-success arrowed-in arrowed-in-right">{{($centroCusto->endereco) }}</span>
                       
                        <img class="media-object" style="height: 150px; width: 300%; display: block;" src="{{ url('/upload/'.$centroCusto->logotipo)}}" data-holder-rendered="true">
                        <div class="caption" style="background-color: #e9e9e9">
                            <div class="clearfix">
                                <strong class="search-title">
                                    <a href="#" wire:click="selecionarCentroCusto({{ json_encode($centroCusto) }})" style="color: rgb(10, 109, 223)">{{ \Illuminate\Support\Str::upper($centroCusto->nome) }}</a>
                                </strong>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @if($loop->iteration % 4 == 0)
                </div><div class="row">
            @endif
        @endforeach
    </div>
    
    
    
</div>
<style>

</style>