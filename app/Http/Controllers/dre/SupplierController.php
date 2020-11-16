<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Uf;
use Illuminate\Support\Facades\Auth;



class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        //  echo  (string) Str::uuid();
        //  exit();

        $suppliers = Supplier::paginate(10);
        return view('erp.suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }


    public function create()
    {
        $loggedId = Auth::id();
        $user = User::find($loggedId);

        if ($user->can('forn_create')) {
            $ufs = Uf::all();
            return view('erp.suppliers.create', [
                'ufs' => $ufs
            ]);
        } else {
            return redirect()->route('suppliers.index');
        }
    }


    public function store(Request $request)
    {
        $data = $request->input();
        
     //   echo '<pre>';
     //   print_r($data);
     //   exit();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'string', 'max:200'],
            'phone' => ['required', 'string', 'max:20'],
            'celphone' => ['string', 'max:20'],
            'uf' => ['required', 'string', 'max:50', 'exists:ufs,nome'],
            'municipio' => ['required', 'string', 'max:150', 'exists:municipios,id']
        ]);

        if ($validator->fails()) {
            return redirect()->route('suppliers.create')
                ->withErrors($validator)
                ->withInput();
        }


        $sinais = ['/', '.', '-'];

        // cadastra o Fornecedor
        $supplier = new Supplier;
        //$supplier->id = (string) Str::uuid(); -> vai fazer isso diretamente no model
        $supplier->name = $data['name'];
        $supplier->email = $data['email'];
        $supplier->cep = $data['cep'];
        $supplier->street = $data['street'];
        $supplier->number = $data['number'];
        $supplier->compl = $data['compl'];
        $supplier->phone = $data['phone'];
        $supplier->celphone = $data['celphone'];
        $supplier->pfj = $data['pesfj'];
        $supplier->municipio_id = $data['municipio_id'];
        if ($supplier->pfj == 'F') {
            $supplier->cnpj = intval(str_replace($sinais, "", $data['cpf']));
        } else {
            $supplier->cnpj = intval(str_replace($sinais, "", $data['cnpj']));
        }
        $supplier->save();

        return redirect()->route('suppliers.index');
    } // fim do store


    public function show($id)
    {
        //
    }


    public function edit($uuid)
    {
        $supplier = Supplier::find($uuid);
        if ($supplier) {
            $municipio = $supplier->municipio()->first();
            if ($municipio) {
                $supplier->municipio_nome = $municipio->nome;
                $supplier->uf = $municipio->uf;
            }
            $ufs = Uf::all();
            return view('erp.suppliers.edit', [
                'supplier' => $supplier,
                'ufs' => $ufs
            ]);
        }
        return redirect()->route('suppliers.index');
    }



    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
