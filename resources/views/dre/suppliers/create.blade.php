@extends('adminlte::page')

@section('title', 'Incluir Fornecedor')

@section('content_header')
    <h1>Incluir Fornecedor</h1>

@endsection

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i>Ocorreram erros:</h5>
            <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
            </ul>
        </div>
    @endif


    <div class="card">
        <div class="card-body">
            <form action="{{ route('suppliers.store')}}" method="POST" class="form-horizontal">
                @csrf



                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <div class="form-check col-sm-2">
                        <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="pesfj" value="J" checked="" onclick="hideCpf()">
                          Pessoa Jurídica</label>
                    </div>
                    <div class="form-check col-sm-2">
                        <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="pesfj" value="F" onclick="hideCnpj()">
                        Pessoa Física</label>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">CNPJ / CPF</label>
                    <div id="cnpj" class="col-sm-3">
                        <input type="text" name="cnpj" value="{{old('cnpj')}}" data-inputmask="'mask': '99.999.999/9999-99'" data-mask="" im-insert="true" class="form-control @error('refcode') is-invalid @enderror" />
                    </div>
                    
                    <div id="cpf" class="col-sm-3">
                        <input type="text" name="cpf" value="{{old('cnpj')}}" data-inputmask="'mask': ['999.999.999-99']" data-mask="" class="form-control @error('refcode') is-invalid @enderror" />
                    </div>
                    <label class="col-sm-1 col-form-label">Nome </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">E-mail </label>
                    <div class="col-sm-10">
                        <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">CEP </label>
                    <div class="col-sm-10">
                        <input type="text" name="cep" value="{{old('cep')}}" class="form-control @error('cep') is-invalid @enderror" />
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Endereço </label>
                    <div class="col-sm-4">
                        <input type="text" name="street" value="{{old('street')}}" class="form-control @error('street') is-invalid @enderror" />
                    </div>
                    <label class="col-sm-1 col-form-label">Nº </label>
                    <div class="col-sm-2">
                        <input type="text" name="number" value="{{old('number')}}" class="form-control @error('number') is-invalid @enderror" />
                    </div>
                    <label class="col-sm-1 col-form-label">Compl</label>
                    <div class="col-sm-2">
                        <input type="text" name="compl" value="{{old('compl')}}" class="form-control @error('compl') is-invalid @enderror" />
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Telefone </label>
                    <div class="col-sm-4">
                        <input type="text" name="phone" value="{{old('phone')}}" class="form-control @error('phone') is-invalid @enderror" />
                    </div>
                    <label class="col-sm-1 col-form-label">Celular </label>
                    <div class="col-sm-5">
                        <input type="text" name="celphone" value="{{old('celphone')}}" class="form-control @error('celphone') is-invalid @enderror" />
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">UF</label>
                    <div class="col-sm-4">
                        <select class="form-control selectUf" name="uf" style="width: 100%;" onchange="ajaxMunicipio()">
                          <option selected="selected">{{old('uf')}}</option>
                          @foreach($ufs as $uf)
                            <option value="{{$uf->nome}}">{{$uf->nome}}</option>
                          @endforeach
                        </select>
                    </div>

                    <label class="col-sm-1 col-form-label">Município</label>
                    <div class="col-sm-5">
                        <select class="form-control selectMun" id="selMunicipio" name="municipio" style="width: 100%;" onchange="acceptMun()">
                          <option selected="selected">{{old('municipio')}}</option>
                        </select>
                        <input type="hidden" id="municipio_id" name="municipio_id" value={{old('municipio')}} />
                    </div>
                </div>  


                
                <div class="form-group row">
                </div>


                <div class="form-group row">
                    <div class="col-sm-9">
                        <input type="submit" value="Salvar" class="btn btn-success" />
                    </div>
                </div>
                
            </form>
        </div>
    </div>





      
<!-- Select2 -->

@endsection


@section('js')

<script>
const cs = (el)=> document.querySelectorAll(el);
const c = (el)=> document.querySelector(el);
hideCpf();

function acceptMun() {
let selectMun = c('select.selectMun');
    let idMun =  document.getElementById('municipio_id');
    let descr = selectMun.options[selectMun.selectedIndex].text;
    idMun.value = selectMun.value;
    document.getElementById("selMunicipio").selectedIndex = 0;  // volta p/exibicao do primeiro item
    console.log('id:'+ idMun.value);
    console.log('selectmun:'+ selectMun.value);
}



function ajaxMunicipio() {
    let selectedUf = c('select.selectUf').value;
    var parametro = {
        uf: selectedUf
        };  
    $.ajax({
        url:"{{ url('/publicfinds/municipios') }}",
        type:'GET',
        data: parametro,
        dataType:'json',
        success:function(json) {
            let html = '';
            for(var i in json) {
                if ("id" in json[i]) {
                    html += '<option class="opt" value="'+json[i].id+'">'+json[i].nome+'</option>';
                }
            }

            let obj = document.getElementById("selMunicipio");
            obj.innerHTML = html;
        }
    });
}



function hideCpf() {
    document.getElementById("cpf").style.display = 'none';
    document.getElementById("cnpj").style.display = 'block';
}
function hideCnpj() {
    document.getElementById("cnpj").style.display = 'none';
    document.getElementById("cpf").style.display = 'block';
}



$('[data-mask]').inputmask()

</script>



@endsection
    

