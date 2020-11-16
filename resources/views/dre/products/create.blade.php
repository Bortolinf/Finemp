@extends('adminlte::page')

@section('title', 'Incluir Produto')

@section('content_header')
    <h1>Incluir Produto</h1>

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
            <form action="{{ route('products.store')}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Referência</label>
                    <div class="col-sm-3">
                        <input type="text" name="refcode" value="{{old('refcode')}}" class="form-control @error('refcode') is-invalid @enderror" />
                    </div>
                    <label class="col-sm-1 col-form-label">Descrição </label>
                    <div class="col-sm-6">
                        <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" />
                    </div>
                </div>

                <div class="form-group row">
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Descrição Completa</label>
                    <div class="col-sm-10">
                        <textarea type="text" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                    </div>
                </div>


                <div class="form-group row" style="margin-bottom: -3px;">

                    <label class="col-sm-2 col-form-label">Unidade Medida</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="un" style="width: 100%;">
                          <option selected="selected">{{old('un')}}</option>
                          @foreach($unids as $unid)
                          <option>{{$unid->unid}}</option>
                          @endforeach
                        </select>
                    </div>

                    <label class="col-sm-1 col-form-label">NCM</label>
                    <div class="input-group col-sm-6">
                            <input type="text" name="ncm" onchange="ajaxLerNcm()" maxlength="8" value="{{old('ncm')}}" class="form-control @error('ncm') is-invalid @enderror" />
                            <span class="input-group-append">
                                <button onclick="ajaxNcm()" type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal"><i class="icon fas fa-search"></i></button>
                            </span>
                    </div>
                </div>  

                <!--
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <small id="ncmHelp" class="form-text text-info ncmdsc">{{old('ncmDescr')}}</small>
                    </div>
                </div>
                <div class="form-group row">
                !-->

                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <small id="ncmHelp" class="form-text text-info ncmdsc">{{old('ncmDescr')}}</small>
                    </div>
                </div>



                <div class="form-group row">
                </div>


                <div class="form-group row" style="margin-bottom: -3px;">

                    <label class="col-sm-2 col-form-label">Preço</label>
                    <div class="col-sm-3">
                        <input type="text" name="price" value="{{old('price')}}" class="form-control @error('price') is-invalid @enderror" />
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


    @include('/erp/partials/modalncm')


      
<!-- Select2 -->

@endsection


@section('js')
<script>
    $(document).ready(function() { $('select.select2').select2(); });
</script>



<script>
const cs = (el)=> document.querySelectorAll(el);
const c = (el)=> document.querySelector(el);
function selncm() {
    let selectedNcm = c('select.selectncm').value;
    arrayNcm = selectedNcm.split('||');
    c('input[name="ncm"]').value = arrayNcm[0];
    c('small.ncmdsc').innerHTML = arrayNcm[1];
}


function ajaxNcm() {
    $.ajax({
        url:"{{ url('/publicfinds/ncms') }}",
        type:'GET',
        data:{},
        dataType:'json',
        success:function(json) {
            let html = '<select class="form-control select2 selectncm" style="width: 100%;">';
            for(var i in json) {
                if ("id" in json[i]) {
                    html += '<option class="opt" value="'+json[i].codncm+'||'+json[i].description+'">'+json[i].codncm+' - '+json[i].description+'</option>';
                }
            }

            let obj = document.getElementById("findNcmContent");
            obj.innerHTML = html;
            $('select.select2').select2();

        }
    });
}


function ajaxLerNcm() {
    let ncm = c('input[name="ncm"]').value;
    $.ajax({
        url:"{{ url('/publicreadings/ncm') }}" + "/" + ncm,
        type:'GET',
        data:{ },
        dataType:'json',
        success:function(json) {
            if ("erro" in json) {
                c('small.ncmdsc').innerHTML = json.erro;
            } else {
                c('small.ncmdsc').innerHTML = json.description;
            }
        }
    });
}


</script>



@endsection
    

