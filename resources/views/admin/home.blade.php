@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Painel')



@section('content_header')
@endsection


@section('content')

@if(session('warning'))
    <div class="alert alert-info alert-dismissible">
        {{session('warning')}}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    </div>
@endif

<h1>
Painel Inicial do Sistema
</h1>

@endsection

