<!DOCTYPE html>
<html lang="en">
<title>FinEmp Finanças Empresariais</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{ url('/css/w3.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee,.fa-dollar,.fa-tasks,.fa-list  {font-size:200px}
</style>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-indigo w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-indigo" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="#" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a>
    <a href="#visaogeral" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Visão Geral</a>
    <a href="#recursos" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Recursos</a>
    <a href="{{ route('login') }}" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Login</a>
    <a href="{{ route('registerTenant') }}" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Registrar Nova Empresa</a>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="#visaogeral" class="w3-bar-item w3-button w3-padding-large">Visão Geral</a>
    <a href="#recursos" class="w3-bar-item w3-button w3-padding-large">Recursos</a>
    <a href="{{ route('login') }}" class="w3-bar-item w3-button w3-padding-large">Login</a>
    <a href="{{ route('registerTenant') }}" class="w3-bar-item w3-button w3-padding-large">Registrar</a>
  </div>
</div>

<!-- Header -->
<header class="w3-container w3-indigo w3-center" style="padding:128px 16px">
  <h1 class="w3-margin w3-jumbo">FinEmp</h1>
  <p class="w3-xlarge">Finanças Empresariais</p>
  <a href="{{ route('login') }}">
    <button class="w3-button w3-white w3-padding-large w3-large w3-margin-top">Entrar</button>
  </a>

  <a href="{{ route('registerTenant') }}">
    <h5 class="w3-padding-32">
    Registrar nova Empresa</h5>
  </a>

</header>

<!-- First Grid -->
<div id="visaogeral" class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>O que ele faz?</h1>
      <h5 class="w3-padding-32">Através do FinEmp você terá sempre em mãos o resultado financeiro de sua empresa. Acompanhe cada despesa/receita em análises comparativas e estruturadas.
        </h5>
      <h1>Como funciona?</h1>
      <h5 class="w3-padding-32">O sistema rebebe os lançamentos diários das suas despesas, receitas e custos, e depois fornece o resultado financeiro da sua empresa.</h5>

    </div>

    <div class="w3-third w3-center">
      <img class="w3-col s8 w3-padding-64" src="{{ url('/img/report.svg') }}">
    </div>
  </div>
</div>

<!-- Second Grid -->
<div id="recursos" class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-third w3-center">
      <img class="w3-col s8 w3-padding-64" src="{{ url('/img/settings.svg') }}">
    </div>

    <div class="w3-twothird">
      <h1 class="w3-padding-32">Principais Recursos</h1>
      <h5>
        <i class="fa fa-cloud w3-text-indigo w3-margin-right"></i>
       Arquitetura moderna - seus dados ficam salvos na nuvem, e podem ser acessados através de computador, tablet ou celular.  
      </h5>

      <h5>
        <i class="fa fa-lock w3-text-indigo w3-margin-right"></i>
        Painel gerencial - crie seus usuários e defina as permissões de cada um.
      </h5>

      <h5>
        <i class="fa fa-sitemap w3-text-indigo w3-margin-right"></i>
        Controle por Filiais - controle os resultados por filiais ou o resultado global da empresa
      </h5>

      <h5>
        <i class="fa fa-list-ul w3-text-indigo w3-margin-right"></i>
        Plano de Contas Personalizado - você define seu plano de contas de acordo com o seu negócio
      </h5>

      <h5>
        <i class="fa fa-file w3-text-indigo w3-margin-right"></i>
        Vários modelos de Relatórios disponíveis: DRE mensal, DRE acumulado, DRE comparativo 
      </h5>

    </div>
  </div>
</div>



<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
  <div class="w3-xlarge w3-padding-32">
    <a href="https://www.facebook.com/bortolin.furlanetto" target="blank">
      <i class="fa fa-facebook-official w3-hover-opacity"></i>
    </a>
    <i class="fa fa-instagram w3-hover-opacity"></i>
    <a href="https://www.linkedin.com/in/bortolin-furlanetto-03482a137/" target="blank">
      <i class="fa fa-linkedin w3-hover-opacity"></i>
    </a>
 </div>
 <p>Feito com ♥ por <a href="mailto:bortolin@yahoo.com">Bortolin Furlanetto</a></p>
</footer>

<script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>

</body>
</html>