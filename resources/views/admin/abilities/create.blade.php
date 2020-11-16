@extends('adminlte::page')

@section('title', 'Nova Permissão')

@section('content_header')
    <h1>Nova Permissão</h1>

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
            <form action="{{ route('abilities.store')}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nome</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9">
                        <input type="submit" value="cadastrar" class="btn btn-success" />
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection