@extends('adminlte::page')

@section('title', 'Incluir nova Filial')

@section('content_header')
    <h1>Incluir nova Filial</h1>

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
            <form action="{{ route('companies.store')}}" method="POST" class="form-horizontal"  autocomplete="off">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{old('name')}}" class="form-control @error('refcode') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
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
