<?php

use Illuminate\Support\Str;

?>

@extends('layouts.app')
@section('title','Listar Produtos')
@section('content')
<div class="alert alert-danger" role="alert">
  PRODUTOS COM QUANTIDADE CRITICA ,ACTUALIZA O SEU STOCK
</div>

<div class>
  <div class="row">
    <form id="adimitirCandidatos" method="POST" action>
      <input type="hidden" name="_token" value />

      <div class="col-xs-12 widget-box widget-color-green" style="left: 0%">
        <div class="clearfix">
          <div class="pull-right tableTools-container"></div>
        </div>
        <div class="table-header widget-header">
        Quantidade Criticas (Total:{{count($existenciaStock)}})
        </div>

        <!-- div.dataTables_borderWrap -->
        <div>
          <table class=" tabela1 table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>COD. Produto</th>
                <th>Designação</th>
                <th style="text-align: right;">Quantidade Critica</th>
                <th style="text-align: right;">Quantidade Minima</th>
              </tr>
            </thead>
            <tbody>
              @foreach($existenciaStock as $existstock)
              <tr>
                <td>{{$existstock->produtos->referencia}}</td>
                <td>{{ Str::upper($existstock->produtos->designacao)}}</td>
                <td style="text-align: right;">{{ number_format($existstock->produtos->quantidade_critica, 1, ',', '.')}}</td>
                <td style="text-align: right;">{{number_format($existstock->produtos->quantidade_minima, 1, ',', '.')}}</td>
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

@endsection