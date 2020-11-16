@extends('adminlte::page')

@section('title', 'Filiais')

@section('content_header')
    <h1>
        Filiais
        @can('Editar Filiais')
            <a href="{{ route('companies.create')}}" class="btn btn-dm btn-success">Incluir nova Filial</a>
        @endcan
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
                @foreach($companies as $company)
                    <tr>
                        <td>{{$company->id}}</td>
                        <td>{{$company->name}}</td>
                        <td>
                        @can('Editar_Filiais')    
                            <a href="{{ route('companies.edit', ['company' => $company->id]) }}" class="btn btn-sm btn-info">Editar</a>
                        @endcan
                        @can('Editar_Filiais')
                            <form class="d-inline" method="POST" 
                            action="{{ route('companies.destroy', ['company' => $company->id]) }}" 
                            onsubmit="return confirm('Tem certeza que deseja Excluir?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            <!-- codigo mágico p/incluir links da paginação -->
            {{ $companies->links() }}
        </div>
    </div>

@endsection