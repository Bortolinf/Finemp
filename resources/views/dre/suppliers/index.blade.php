@extends('adminlte::page')

@section('title', 'Fornecedores')

@section('content_header')
    <h1>
        Meus Fornecedores
        @can('forn_create')
            <a href="{{ route('suppliers.create')}}" class="btn btn-dm btn-success">Incluir Fornecedor</a>
        @endcan
        @can('forn_list')
        <a href="{{ route('suppliers.toExcel')}}" class="btn btn-dm btn-success"><i class="fas fa-fw fa-file-excel"></i> </a>
        @endcan
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover">
                <tr>
                    <th>Nome</th>
                    <th>Cnpj/Cpf</th>
                    <th>Fone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->cnpj}}</td>
                        <td>{{$supplier->phone}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>
                        @can('forn_edit')    
                            <a href="{{ route('suppliers.edit', ['supplier' => $supplier->uuid]) }}" class="btn btn-sm btn-info">Editar</a>
                        @endcan
                        @can('forn_delete')
                            <form class="d-inline" method="POST" 
                            action="{{ route('suppliers.destroy', ['supplier' => $supplier->uuid]) }}" 
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
            {{ $suppliers->links() }}
        </div>
    </div>

@endsection