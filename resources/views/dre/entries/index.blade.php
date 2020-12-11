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
                    <th>Valor</th>
                    <th>Filial</th>
                    <th>Ações</th>
                </tr>
                @foreach($entries as $entry)
                    <tr class="entry{{$entry->id}}">
                        <td>{{$entry->date}}</td>
                        <td>{{$entry->account->description}}</td>
                        <td>{{$entry->value}}</td>
                        <td>{{$entry->company->name}}</td>
                        <td>
                        @can('Editar_Lanctos')    
                           <!-- <a href="{{ route('entries.edit', ['entry' => $entry->id]) }}" class="btn btn-sm btn-info">Editar</a> -->

                            <a href="#" class="edit-modal btn btn-warning btn-sm" 
                               data-id="{{$entry->id}}"
                               data-value="{{$entry->value}}"
                               data-date="{{$entry->date}}"
                               data-info="{{$entry->info}}"
                               data-account="{{$entry->account_id}}"
                               data-company="{{$entry->company_id}}"
                                >
                              Editar </a>

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



@include('/dre/entries/modalentry')


@endsection







@section('js')
<script>
{{-- ajax Form Add Post--}}
  $(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('#id').val('');
    $('#date').val(today());  // assumir data de hoje
    $('#value').val(0);
    $('#info').val('');
    $('.form-horizontal').show();
    $('.modal-title').text('Incluir Lançamento');
    $('.error').hide();


  });




  $("#add").click(function() {
    document.querySelector('p.error').innerHTML = '';
    let route = '';
    let isedit = false;
    if ($('#id').val() == '') {
      route = '{{ route('addEntry')}}'
    } else {
      route = '{{ route('updateEntry')}}'
      isedit = true;
    }

    $.ajax({
      type: 'POST',
      url: route,
      headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
      data: {
        'id': $('input[name=id]').val(),
        'value': $('input[name=value]').val(),
        'date': $('input[name=date]').val(),
        'info': $('textarea[name=info]').val(),
        'company': $('select[name=company]').val(),
        'account': $('select[name=account]').val()
      },
      success: function(data){
        console.log(data.value);
        if ((data.errors)) {
            le = "<ul>";
            data.errors.forEach(e => {
                le += '<li>' + e;
            });
            le += '</ul>';
            document.querySelector('p.error').innerHTML = le;
            $('.error').show();
        } else {
          let url_delete = '{{ route("entries.destroy", ['entry' => ":id"]) }}';
          url_delete = url_delete.replace(':id', data.id);
          let confirm_del = " onsubmit=\"return confirm('Tem certeza que deseja Excluir?')";
          $('.error').hide();
          let conteudo_linha = "<tr class='entry" + data.id + "'>" +
                        "<td>" + data.date + "</td>"+
                        "<td>" + data.account_description + "</td>"+
                        "<td>" + data.value + "</td>"+
                        "<td>" + data.company_name + "</td>"+
                        "<td>" +
                        
                          "<a href='#' class='edit-modal btn btn-warning btn-sm'" + 
                               "data-id='" + data.id + "'" +
                               "data-value='" + data.value + "'" +
                               "data-date='" + data.date + "'" +
                               "data-info='" + data.info + "'" +
                               "data-account='" + data.account_id + "'" +
                               "data-company='" + data.company_id + "'" +
                               ">Editar </a>" +
                        "<form class='d-inline' method='POST' action='" + url_delete + "'>" +
                           ' @csrf ' + ' @method("DELETE")' + "<button class='btn btn-sm btn-danger'>Excluir</button></form>" +
                        "</td></tr>";
          if(isedit) {
            $('.entry' + data.id).replaceWith(conteudo_linha);
            $('#create').modal('hide');
          } else
          {
            $('#table').append(conteudo_linha);
          }
        }
      },
    });

    $('input[name="value"]').val('');
    $('textarea[name="info"]').val('');
    $('input[name="value"]').focus();

  });



// funcoes de edicao de registro
$(document).on('click', '.edit-modal', function() {
//  /\/\/ continuar daqui que nao esta puxando os dados
  $('#id').val($(this).data('id'));
  $('#value').val($(this).data('value'));
  $('#date').val($(this).data('date'));
  $('#edit').modal('show');
  $('#info').val($(this).data('info'));
  $('#company').val($(this).data('company'));
  $('#account').val($(this).data('account'));

    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Alterar Lançamento');
    $('.error').hide();

  });


// retorna data atual
  function today() {
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth()+1; 
    let yyyy = today.getFullYear();
    if(dd<10) 
    {
        dd='0'+dd;
    } 
    if(mm<10) 
    {
      mm='0'+mm;
    } 
    return yyyy+'-'+mm+'-'+dd;
  }


</script>






@endsection


