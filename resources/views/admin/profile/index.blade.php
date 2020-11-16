@extends('adminlte::page')

@section('title', 'Meu Perfil')


@section('content_header')
<h1>Meu Perfil</h1>
@endsection


@section('content')


@if($errors->any())
<div class="alert alert-danger">
    <h5><i class="icon fas fa-ban"></i>Ocorreram erros:</h5>
    <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
    </ul>
</div>
@endif


@if(session('warning'))
    <div class="alert alert-info">
        {{session('warning')}}
    </div>
@endif


<div class="card">
<div class="card-body">
    <form action="{{ route('profile.save')}}" method="POST" class="form-horizontal">
        @csrf
        @method('PUT')
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nome Completo</label>
            <div class="col-sm-9">
                <input type="text" name="name" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">E-mail</label>
            <div class="col-sm-9">
                <input type="email" name="email" value="{{$user->email}}" class="form-control @error('email') is-invalid @enderror" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nova Senha</label>
            <div class="col-sm-9">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Confirmação da Senha</label>
            <div class="col-sm-9">
                <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="submit" value="Salvar" class="btn btn-success" />
            </div>
        </div>
    </form>
</div>
</div>


@endsection

