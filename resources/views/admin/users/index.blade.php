@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>
        Meus usuários
        <a href="{{ route('users.create')}}" class="btn btn-dm btn-success">Novo usuário</a>
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-sm btn-info">Editar</a>
                            @if($user->id != $loggedId)
                                <form class="d-inline" method="POST" 
                                action="{{ route('users.destroy', ['user' => $user->id]) }}" 
                                onsubmit="return confirm('Tem certeza que deseja Excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            <!-- codigo mágico p/incluir links da paginação -->
            {{ $users->links() }}
        </div>
    </div>

@endsection