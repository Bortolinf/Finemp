@extends('adminlte::page')

@section('title', 'Dre Simplificado')

@section('content_header')
    <h1>
        DRE Simplificado
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">


            <table class="table table-hover table-sm" id="table">
                <tr>
                    <th>Conta</th>
                    <th>Descrição</th>
                    <th>Saldo Inicial</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Saldo Final</th>
                </tr>

                @foreach($entries as $entry)
                    <tr class="entry">
                        <td>{{$entry->id_account}}</td>
                        <td>{{$entry->description}}</td>
                        <td>{{$entry->saldo_inicial}}</td>
                        <td>{{$entry->entrada}}</td>
                        <td>{{$entry->saida}}</td>
                        <td>{{$entry->saldo_final}}</td>
                    </td>
                @endforeach

            </table>
        </div>
        <div class="card-footer">
            <!-- codigo mágico p/incluir links da paginação -->
        </div>
    </div>





@endsection







@section('js')
<script>



</script>






@endsection


