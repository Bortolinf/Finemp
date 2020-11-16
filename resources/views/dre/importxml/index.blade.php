@extends('adminlte::page')

@section('title', 'Importar Xmls')

@section('content_header')
    <h1>
        Importação de Arquivos XML de Fornecedores
    </h1>
    
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


    @if($msg)
        @if($msgtipo == 'ok')
            <div class="alert alert-success">
                <h6><i class="icon fas fa-thumbs-up"></i> {{$msg}}</h6>
            </div>
         @else
            <div class="alert alert-warning">
                <h6><i class="icon fas fa-exclamation"></i> {{$msg}}</h6>
            </div>
        @endif
    @endif
        
    @can('import_xml')
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('import_xml')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <input type="file" class="btn btn-block bg-gradient-primary" name="file[]" multiple />
                        </div>
                    </div>


                    <div class="form-group row">
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-9">
                            <input type="submit" value="Importar" class="btn btn-success" />
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    @endcan

@endsection
