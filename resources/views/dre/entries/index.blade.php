@extends('adminlte::page')

@section('title', 'Lançamentos')

@section('content_header')
    <h1>
        Lançamentos
        @can('Editar_Lanctos')
            <a href="#" class="create-modal btn btn-success btn-sm"> Incluir Lançamentos </a>
        @endcan
    </h1>

@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('entries.index') }}">
               @csrf
                <div class="row">
                  <div class="col-md-4">
                    <input class="form-control form-control-sm" type="search" placeholder="Pesquisar ..." name="q" value="{{ $q }}">
                  </div>
              
                  <div class="col-md-2 col-3">
                    <select name="sortBy" class="form-control form-control-sm" value="{{ $sortBy }}">
                      @foreach(['Data', 'Conta', 'Valor'] as $col)
                        <option @if($col == $sortBy ) selected @endif value="{{ $col }}">{{ ucfirst($col) }}</option>
                      @endforeach
                    </select>
                  </div>
              
                  <div class="col-md-2 col-3">
                    <select name="orderBy" class="form-control form-control-sm" value="{{ $orderBy }}">
                      @foreach(['asc', 'desc'] as $order)
                        <option @if($order == $orderBy) selected @endif value="{{ $order }}">{{ ucfirst($order) }}</option>
                      @endforeach
                    </select>
                  </div>
              
                  <div class="col-md-2 col-3">
                    <select name="perPage" class="form-control form-control-sm" value="{{ $perPage }}">
                      @foreach(['20','50','100','250'] as $page)
                        <option @if($page == $perPage) selected @endif value="{{ $page }}">{{ $page }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-2 col-3">
                    <button type="submit" class="w-100 btn btn-sm bg-blue">Filtrar</button>
                  </div>
                </div>
              </form>

              <div class="form-group row">
            </div>



            <table class="table table-hover table-sm" id="table">
                <tr>
                    <th>Data</th>
                    <th>Conta</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
                @foreach($entries as $entry)
                    <tr>
                        <td>{{$entry->date}}</td>
                        <td>{{$entry->account->description}}</td>
                        <td>{{$entry->es}}</td>
                        <td>{{$entry->value}}</td>
                        <td>
                        @can('Editar_Lanctos')    
                            <a href="{{ route('entries.edit', ['entry' => $entry->id]) }}" class="btn btn-sm btn-info">Editar</a>

                        @endcan
                        @can('Editar_Lanctos')
                            <form class="d-inline" method="POST" 
                            action="{{ route('entries.destroy', ['entry' => $entry->id]) }}" 
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
            {{ $entries->links() }}
        </div>
    </div>



@include('/dre/partials/modalentry')


@endsection







@section('js')
<script>
{{-- ajax Form Add Post--}}
  $(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Incluir Lançamento');
  });




  $("#add").click(function() {
    $.ajax({
      type: 'POST',
      url: '{{ route('addEntry')}}',
      headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
      data: {
        'value': $('input[name=value]').val(),
        'es': $('select[name=es]').val(),
        'info': $('textarea[name=info]').val(),
        'company': $('select[name=company]').val(),
        'account': $('select[name=account]').val()
      },
      success: function(data){
        if ((data.errors)) {
          $('.error').removeClass('hidden');
          $('.error').text(data.errors.value);
          $('.error').text(data.errors.es);
          $('.error').text(data.errors.info);
          $('.error').text(data.errors.company);
          $('.error').text(data.errors.account);
        } else {
          let url_edit = '{{ route("entries.edit", ['entry' => ":id"]) }}';
          url_edit = url_edit.replace(':id', data.id);
          let url_delete = '{{ route("entries.destroy", ['entry' => ":id"]) }}';
          url_delete = url_delete.replace(':id', data.id);
          let confirm_del = " onsubmit=\"return confirm('Tem certeza que deseja Excluir?')";
          $('.error').remove();
          $('#table').append("<tr>"+
                        "<td>" + data.date + "</td>"+
                        "<td>" + data.account_description + "</td>"+
                        "<td>" + data.es + "</td>"+
                        "<td>" + data.value + "</td>"+
                        "<td><a href='" + url_edit +"' class='btn btn-sm btn-info'>Editar</a>" +
                        "<form class='d-inline' method='POST' action='" + url_delete + "'>" +
                           ' @csrf ' + ' @method("DELETE")' + "<button class='btn btn-sm btn-danger'>Excluir</button></form>" +
                        "</td></tr>"                        
                         );
        }
      },
    });
    $('input[name="value"]').val('');
    $('textarea[name="info"]').val('');
    $('input[name="value"]').focus();

  });

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


function ajaxCreate() {
}



@endsection


