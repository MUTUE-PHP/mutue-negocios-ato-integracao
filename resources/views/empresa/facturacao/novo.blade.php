@extends('layout.appFaturacao')
@section('title','Fazer Factura')
@section('content')
<section class="content">
    <facturacao-novo-component :guard="{{$guard}}" :armazens="{{$armazens}}" :clientes="{{ $clientes }}" :formaspagamentos="{{$formaspagamentos}}"></facturacao-novo-component>
</section>
@endsection
