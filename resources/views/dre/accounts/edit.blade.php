@extends('adminlte::page')

@section('title', 'Editar Conta')

@section('content_header')
    <h1>Editar Conta</h1>

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
            <form action="{{ route('accounts.update', ['account'=>$account->id_account])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Código </label>
                    <div class="col-sm-9">
                        <input type="text" name="id_account" value="{{$account->id_account}}" class="form-control" disabled />
                    </div>
                </div>

                <div class="form-group row">
                </div>
  
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Descrição</label>
                    <div class="col-sm-9">
                        <input type="text" name="description" value="{{$account->description}}" class="form-control @error('description') is-invalid @enderror" />
                    </div>
                </div>


                <div class="form-group row">
                <label class="col-sm-3 col-form-label">Analítica / Sintética</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="summary" style="width: 100%;">
                        <option selected="selected">{{$account->summary}}</option>
                        <option>
                            @if ($account->summary == 'A') S
                            @else A 
                            @endif
                        </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Receita / Despesa</label>
                        <div class="col-sm-1">
                            <select class="form-control" name="type" style="width: 100%;">
                            <option selected="selected">{{$account->type}}</option>
                            <option>
                                @if ($account->type == 'R') D
                                @else R 
                                @endif
                            </option>
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


<!-- Select2 -->

@endsection



