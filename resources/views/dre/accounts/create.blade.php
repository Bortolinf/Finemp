@extends('adminlte::page')

@section('title', 'Incluir nova Conta')

@section('content_header')
    <h1>Incluir nova Conta</h1>

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
            <form action="{{ route('accounts.store')}}" method="POST" class="form-horizontal"  autocomplete="off">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Código Conta</label>
                    <div class="col-sm-9">
                        <input type="text" name="id_account" value="{{old('id_account')}}" class="form-control @error('id_account') is-invalid @enderror" />
                    </div>
                </div>
                
                <div class="form-group row">
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Descrição</label>
                    <div class="col-sm-9">
                        <input type="text" name="description" value="{{old('description')}}" class="form-control @error('description') is-invalid @enderror" />
                    </div>
                </div>
                
                <div class="form-group row">
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Analítica/Sintética</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="summary" style="width: 100%;">
                            <option selected="selected">A</option>
                            <option>S</option>
                        </select>
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Natureza: Receita/Despesa</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="type" style="width: 100%;">
                            <option selected="selected">D</option>
                            <option>R</option>
                        </select>
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
