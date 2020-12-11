@extends('adminlte::page')

@section('title', 'Dre Comparativo')

@section('content_header')
    <h1>
        DRE Comparativo
    </h1>

@endsection

@section('content')




<div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Filtros</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="{{ route('dre_comparativo')}}" method="POST" class="form-horizontal"  autocomplete="off">
            @csrf

        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Período de Referência</label>
                    <input type="month" id="per_ref" name="per_ref" value="{{$per_ref}}" class="form-control" />
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-12">
                    <label>Selecionar Filiais</label>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    @foreach($companies as $company)
                        <div class="form-check">
                        <label class="form-check-label">
                        <input type="checkbox" name="fil{{$company->id}}" value=@if(in_array($company->id, $filterCompanies))"1" checked @else "0" @endif class="form-check-input" />
                        {{$company->name}}
                        </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div> 

        
        <!-- /.row -->
        <div class="row">
            <div class="col-md-2">
                <label></label>
                <div class="form-group">
                <input class="btn btn-block btn-primary" type="submit" value="Gerar DRE" />
                </div>
            </div>
        </div>
      
        </form>
    
    </div>
</div>





    <div class="card">
        <div class="card-body">


            <table class="table table-hover table-sm" id="table">
                <tr>
                    <th>Conta</th>
                    <th>Descrição</th>
                    <th>Per.Refer</th>
                    <th>Per.Acumul</th>
                </tr>

                @foreach($accounts as $account)
                    <tr class="entry">
                        <td>{{$account->id_account}}</td>
                        <td>{{$account->description}}</td>
                        <td>{{$account->saldo_final}}</td>
                        <td>{{$account->saldo_final_acm}}</td>
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


