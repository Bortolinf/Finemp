@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <h1>
        Meus Produtos
        @can('prod_create')
            <a href="{{ route('products.create')}}" class="btn btn-dm btn-success">Incluir Produto</a>
        @endcan
        @can('prod_list')
        <a href="{{ route('products.toExcel')}}" class="btn btn-dm btn-success"><i class="fas fa-fw fa-file-excel"></i> </a>
        <a href="{{ route('products.toPdf')}}" class="btn btn-dm btn-success"><i class="fas fa-fw fa-file-pdf"></i> </a>
        @endcan
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->refcode}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->stock}}</td>
                        <td>
                        @can('prod_edit')    
                            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-info">Editar</a>
                        @endcan
                        @can('prod_delete')
                            <form class="d-inline" method="POST" 
                            action="{{ route('products.destroy', ['product' => $product->id]) }}" 
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
            {{ $products->links() }}
        </div>
    </div>

@endsection