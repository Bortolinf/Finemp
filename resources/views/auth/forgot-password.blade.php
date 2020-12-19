@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')


<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Fin</b>Emp</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Esqueceu sua Senha? Aqui você poderá obeter uma nova senha.</p>

      @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
      @endif


      <form method="POST" action="{{ route('password.request') }}">  
        @csrf        
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Registrar Nova Senha</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="painel/login">Login</a>
      </p>
      <p class="mb-0">
        <a href="painel/register" class="text-center">Registrar Novo Usuário</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->



@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
