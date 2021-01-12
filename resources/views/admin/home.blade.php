@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Painel')



@section('content_header')
@endsection


@section('content')

@if(session('warning'))
    <div class="alert alert-info alert-dismissible">
        {{session('warning')}}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    </div>
@endif

<h1>
Painel Inicial do Sistema
</h1>


@can('DashBoard')

<!--  primeiro grafico  -->
<div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Dados das Últimas Semanas</h3>
            <a href="javascript:void(0);">View Report</a>
        </div>
        </div>
        <div class="card-body">
        <div class="d-flex">
            <p class="d-flex flex-column">
            <span class="text-bold text-lg">820</span>
            <span>Visitors Over Time</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success">
                <i class="fas fa-arrow-up"></i> 12.5%
            </span>
            <span class="text-muted">Since last week</span>
            </p>
        </div>
        <!-- /.d-flex -->

        <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="visitors-chart" height="200" width="398" class="chartjs-render-monitor" style="display: block; width: 398px; height: 200px;"></canvas>
        </div>

        <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
            <i class="fas fa-square text-primary"></i> Receitas
            </span>

            <!-- <span><i class="fas fa-square text-gray"></i> Outra Coisa</span>  -->
        </div>
        </div>
       </div>
    </div>


    <!--  segundo grafico  -->
    <div class="col-lg-6">
      <div class="card">
         <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Receitas x Despesas</h3>
            <a href="javascript:void(0);">View Report</a>
        </div>
        </div>
        <div class="card-body">
        <div class="d-flex">
            <p class="d-flex flex-column">
            <span class="text-bold text-lg">$18,230.00</span>
            <span>Sales Over Time</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success">
                <i class="fas fa-arrow-up"></i> 33.1%
            </span>
            <span class="text-muted">Since last month</span>
            </p>
        </div>
        <!-- /.d-flex -->

        <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="sales-chart" height="200" style="display: block; width: 398px; height: 200px;" width="398" class="chartjs-render-monitor"></canvas>
        </div>

        <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
            <i class="fas fa-square text-primary"></i> Receitas
            </span>

            <span>
            <i class="fas fa-square text-gray"></i> Despesas
            </span>
        </div>
        </div>
      </div>
    </div>
</div>




@endcan



@endsection




@section('js')
<script>

$(function () {
    'use strict'
  
    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }
  
    var mode      = 'index'
    var intersect = true
  
    var $salesChart = $('#sales-chart')
    var salesChart  = new Chart($salesChart, {
      type   : 'bar',
      data   : {
        labels  : [ @foreach($data2['labels'] as $lb1) '{{$lb1}}',  @endforeach],
        datasets: [
          {
            backgroundColor: '#007bff',
            borderColor    : '#007bff',
            data           : [ @foreach($data2['incomes'] as $inc1) {{$inc1}},  @endforeach] 
          },
          {
            backgroundColor: '#ced4da',
            borderColor    : '#ced4da',
            data           : [ @foreach($data2['expenses'] as $exp1) {{$exp1}},  @endforeach]
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips           : {
          mode     : mode,
          intersect: intersect
        },
        hover              : {
          mode     : mode,
          intersect: intersect
        },
        legend             : {
          display: false
        },
        scales             : {
          yAxes: [{
            // display: false,
            gridLines: {
              display      : true,
              lineWidth    : '4px',
              color        : 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks    : $.extend({
              beginAtZero: true,
  
              // Include a dollar sign in the ticks
              callback: function (value, index, values) {
                if (value >= 1000) {
                  value /= 1000
                  value += 'k'
                }
                return '$' + value
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display  : true,
            gridLines: {
              display: false
            },
            ticks    : ticksStyle
          }]
        }
      }
    })
  
    var $visitorsChart = $('#visitors-chart')
    var visitorsChart  = new Chart($visitorsChart, {
      data   : {
        labels  : [ @foreach($data1['labels'] as $lb1) '{{$lb1}}',  @endforeach],
        datasets: [{
          type                : 'line',
          data                : [ @foreach($data1['values'] as $dv1) {{$dv1}},  @endforeach],
          backgroundColor     : 'transparent',
          borderColor         : '#007bff',
          pointBorderColor    : '#007bff',
          pointBackgroundColor: '#007bff',
          fill                : false
          // pointHoverBackgroundColor: '#007bff',
          // pointHoverBorderColor    : '#007bff'
        },
       //   {
       //     type                : 'line',
       //     data                : [60, 80, 70, 67, 80, 77, 100],
       //     backgroundColor     : 'tansparent',
       //     borderColor         : '#ced4da',
       //     pointBorderColor    : '#ced4da',
       //     pointBackgroundColor: '#ced4da',
       //     fill                : false
            // pointHoverBackgroundColor: '#ced4da',
            // pointHoverBorderColor    : '#ced4da'
       //   }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips           : {
          mode     : mode,
          intersect: intersect
        },
        hover              : {
          mode     : mode,
          intersect: intersect
        },
        legend             : {
          display: false
        },
        scales             : {
          yAxes: [{
            // display: false,
            gridLines: {
              display      : true,
              lineWidth    : '4px',
              color        : 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks    : $.extend({
              beginAtZero : true,
              suggestedMax: 200
            }, ticksStyle)
          }],
          xAxes: [{
            display  : true,
            gridLines: {
              display: false
            },
            ticks    : ticksStyle
          }]
        }
      }
    })
  })



</script>

@endsection
