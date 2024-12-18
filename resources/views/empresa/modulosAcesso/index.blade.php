@section('title','módulos de acesso')
<div>
    <div class="alert alert-block alert-danger">
        <center>
            <strong>
                <h5>Por favor, escolha o modulo no qual você deseja realizar suas atividades</h5>
            </strong>
        </center>
    </div>
    <div class="row">
        @foreach($modulos as $modulo)
            <?php
                if($modulo->id == 1){
                    $url = "/empresa/home?email=".$modulo->email."&modulo=".$modulo->id;
                }else{
                    $url = $modulo->url."?email=".$modulo->email."&modulo=".$modulo->id;
                }
                ?>
            <div class="col-xs-6 col-sm-4 col-md-3" style="cursor: pointer">
                <a href="{{ $url }}">
                    <div class="thumbnail search-thumbnail" style="height: 100px;"> <!-- Defina a altura desejada aqui -->
                        <div class="caption" style="background-color: #e9e9e9">
                            <div class="clearfix">
                                <strong class="search-title" style="color: #0004ad">
                                    {{ \Illuminate\Support\Str::upper($modulo->designacao) }}
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












