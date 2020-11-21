@extends('adminlte::page')

@section('title', 'Contas')

@section('content_header')
    <h1>
        Contas
        @can('Editar_Contas')
            <a href="{{ route('accounts.create')}}" class="btn btn-dm btn-success">Incluir nova Conta</a>
        @endcan
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover table-sm">
                <tr>
                    <th>Cód.Conta</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{$account->id_account}}</td>
                        <td>{{$account->description}}</td>
                        <td>{{$account->type}}</td>
                        <td>
                        @can('Editar_Filiais')    
                            <a href="{{ route('accounts.edit', ['account' => $account->id_account]) }}" class="btn btn-sm btn-info">Editar</a>
                        @endcan
                        @can('Editar_Filiais')
                            <form class="d-inline" method="POST" 
                            action="{{ route('accounts.destroy', ['account' => $account->id_account]) }}" 
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
            {{ $accounts->links() }}
        </div>
    </div>

@endsection