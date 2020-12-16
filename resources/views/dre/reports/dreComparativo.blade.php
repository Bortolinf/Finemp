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
            <div class="col-md-4">
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
                    <th class="text-right">{{$cab_date_ant}}</th>
                    <th class="text-right">%</th>
                    <th class="text-right">{{$cab_date}}</th>
                    <th class="text-right">%</th>
                    <th class="text-right">{{$cab_date_acm_ant}}</th>
                    <th class="text-right">%</th>
                    <th class="text-right">{{$cab_date_acm}}</th>
                    <th class="text-right">%</th>
                </tr>

                @foreach($accounts as $account)
                    @php
                        if($ref > 0) { $pct = $account->saldo_final / $ref * 100; } else { $pct = 0; }  
                        if($ref_ant > 0) { $pct_ant = $account->saldo_final_ant / $ref_ant * 100; } else { $pct_ant = 0; }  
                        if($ref_acm > 0) { $pct_acm = $account->saldo_final_acm / $ref_acm * 100; } else { $pct_acm = 0; }
                        if($ref_acm_ant > 0) { $pct_acm_ant = $account->saldo_final_acm_ant / $ref_acm_ant * 100; } else { $pct_acm_ant = 0; }  
                    @endphp
                    <tr class="entry">
                        <td>{{$account->id_account}}</td>
                        <td>{{$account->description}}</td>
                        <td class="text-right">{{number_format($account->saldo_final_ant, 2, ',', '.')}}</td>
                        <td class="text-right"><small>{{round($pct_ant,2)}}%</small></td>
                        <td class="text-right">{{number_format($account->saldo_final, 2, ',', '.')}}</td>
                        <td class="text-right"><small>{{round($pct,2)}}%</small></td>
                        <td class="text-right">{{number_format($account->saldo_final_acm_ant, 2, ',', '.')}}</td>
                        <td class="text-right"><small>{{round($pct_acm_ant,2)}}%</small></td>
                        <td class="text-right">{{number_format($account->saldo_final_acm, 2, ',', '.')}}</td>
                        <td class="text-right"><small>{{round($pct_acm,2)}}%</small></td>
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


