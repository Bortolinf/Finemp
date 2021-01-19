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

    @if ($showInfo) 
        <div class="modal fade show" id="modal-help" style="display: block; padding-right: 17px;" aria-modal="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                <h4 class="modal-title">Como devo criar meu Plano de Contas?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                <p>Olá! <br>A criação correta do plano de contas é fundamental para a geração dos dados.
                   A codificação deve ser feita seguindo uma ordem estruturada conforme o exemplo abaixo:<br>
                   <ul>
                       <li> 1. Resultado (lucro/Prejuízo)
                           <ul>
                       <li> 1.1 LUCRO BRUTO
                        <ul>
                        <li> 1.1.1 Receita Líquida
                        <ul>
                       <li> 1.1.1.1 Receita com Vendas
                       <li> 1.1.1.2 (-)Abatimentos / Impostos
                       </ul>
                       <li> 1.1.2 Custo das Mercadorias Vendidas
                       </ul>

                       <li> 1.2 DESPESAS
                           <ul>
                       <li> 1.2.1 Despesas Operacionais
                           <ul><li> 1.2.1.1 Salários e Ordenados </ul>
                        <li> 1.2.2 Despesas com Vendas
                           <ul><li> 1.2.2.1 Comissões </ul>
                    </ul>
                </p>
                <br>
                Você quer deseja que o sistema gere uma plano de contas conforme esta estrutua?
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Não, obrigado!</button>
               <a href="{{ route('accounts.autocreate') }}" onClick="$('.modal').modal('hide');">   
                <button data-target="#modal-done" type="button" class="btn btn-outline-light">Sim, me ajude com isto.</button>
                </a>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    @endif






    <div class="card">
        <div class="card-body">

            <table class="table table-hover table-sm">
                <tr>
                    <th>Cód.Conta</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Natureza</th>
                    <th>Ações</th>
                </tr>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{$account->id_account}}</td>
                        <td>{{$account->description}}</td>
                        <td>{{$account->summary}}</td>
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



@section('js')

<script>
$(document).ready(function(){
    $("#modal-help").modal('show');

});



</script>

@endsection