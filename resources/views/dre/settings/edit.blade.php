@extends('adminlte::page')

@section('title', 'Editar Configurações')

@section('content_header')
    <h1>Configurações</h1>

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
            <form action="{{ route('settings.update', ['setting'=>$setting->id])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')



                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Conta de Receita Bruta </label>
                      <div class="col-sm-10">
                        <select class="form-control" id="income_account" name="income_account" style="width: 100%;">
                          @foreach($accounts as $account)
                            <option value="{{$account->id_account}}"
                             @if ($account->id_account == $setting->income_account)
                               selected
                             @endif   
                                >{{$account->description}}</option>
                          @endforeach
                          </select>
                      </div>
                </div>
  


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Conta com Total das Despesas </label>
                      <div class="col-sm-10">
                        <select class="form-control" id="expense_account" name="expense_account" style="width: 100%;">
                          @foreach($accounts as $account)
                            <option value="{{$account->id_account}}"
                             @if ($account->id_account == $setting->expense_account)
                               selected
                             @endif   
                                >{{$account->description}}</option>
                          @endforeach
                          </select>
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



