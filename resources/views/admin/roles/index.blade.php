@extends('adminlte::page')

@section('title', 'Grupos de Usuários')

@section('content_header')
    <h1>
        Grupos de Usuários
        <a href="{{ route('roles.create')}}" class="btn btn-dm btn-success">Novo Grupo</a>
    </h1>

@endsection



@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-sm btn-info">Editar</a>
                            <form class="d-inline" method="POST" 
                            action="{{ route('roles.destroy', ['role' => $role->id]) }}" 
                            onsubmit="return confirm('Tem certeza que deseja Excluir?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            <!-- codigo mágico p/incluir links da paginação -->
            {{ $roles->links() }}
        </div>
    </div>

@endsection