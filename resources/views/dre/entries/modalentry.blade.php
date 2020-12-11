
<div class="modal fade" id="create">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Lançamento</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">



          <!--  <form action="{{ route('entries.store')}}" method="POST" class="form-horizontal"  autocomplete="off"> -->
         <form class="form-horizontal" role="form">   
            @csrf
            <div class="form-group row">
              <input type="hidden" id="id" name="id" />
                <label class="col-sm-2 col-form-label">Data</label>
                <div class="col-sm-8">
                    <input type="date" id="date" name="date" value="{{old('date')}}" class="form-control @error('date') is-invalid @enderror" />
                </div>
            </div>
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Conta </label>
                <div class="col-sm-10">
                  <select class="form-control" id="account" name="account" style="width: 100%;">
                    @foreach($accounts as $account)
                      <option value="{{$account->id_account}}">{{$account->description}}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Valor</label>
              <div class="col-sm-10">
                  <input type="text" id="value" name="value" value="{{old('value')}}" class="form-control @error('value') is-invalid @enderror" />
              </div>
           </div>


            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Filial </label>
                <div class="col-sm-10">
                  <select class="form-control" id="company" name="company" style="width: 100%;">
                    @foreach($companies as $company)
                      <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Obs</label>
                <div class="col-sm-10">
                    <textarea type="text" id="info" name="info" class="form-control">{{old('info')}}</textarea>
                </div>
             </div>
          
             <!-- area de geracao dos erros -->
             <div class="form-group row">
               <p class="error text-danger"></p> 
             </div>
             
             <div class="modal-footer justify-content-between">
               <button class="btn btn-success" type="button" id="add">
                 Salvar 
                </button>
                <button class="btn btn-warning" type="button" data-dismiss="modal">
                  Cancelar
                </button>


        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


