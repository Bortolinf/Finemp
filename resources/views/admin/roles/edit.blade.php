@extends('adminlte::page')

@section('title', 'Editar Grupo de Usuários')

@section('content_header')
    <h1>Editar Grupo de Usuário</h1>

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


    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', ['role'=>$role->id])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nome do Grupo</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{$role->name}}" class="form-control @error('name') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Selecionar Recursos Permitidos</label>
                    <div class="col-sm-9">
                        @foreach($abilities  as $ability)
                            <div class="form-check">
                                <input name="{{$ability->name}}" class="form-check-input" type="checkbox" {{$ability->checked}}>
                                <label class="form-check-label">{{$ability->name}}</label>
                          </div>
                        @endforeach
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