@extends('adminlte::page')

@section('title', 'Permissões')

@section('content_header')
    <h1>
        Permissões
        <a href="{{ route('abilities.create')}}" class="btn btn-dm btn-success">Nova Permissão</a>
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
                @foreach($abilities as $ability)
                    <tr>
                        <td>{{$ability->id}}</td>
                        <td>{{$ability->name}}</td>
                        <td>
                            <a href="{{ route('abilities.edit', ['ability' => $ability->id]) }}" class="btn btn-sm btn-info">Editar</a>
                            <form class="d-inline" method="POST" 
                            action="{{ route('abilities.destroy', ['ability' => $ability->id]) }}" 
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
            {{ $abilities->links() }}
        </div>
    </div>

@endsection