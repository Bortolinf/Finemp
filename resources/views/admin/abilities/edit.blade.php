@extends('adminlte::page')

@section('title', 'Editar Permissão')

@section('content_header')
    <h1>Editar Permissão</h1>

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
            <form action="{{ route('abilities.update', ['ability'=>$ability->id])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nome da Permissão</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{$ability->name}}" class="form-control @error('name') is-invalid @enderror" />
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