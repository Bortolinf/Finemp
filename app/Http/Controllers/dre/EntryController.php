<?php

namespace App\Http\Controllers\dre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Entry;
use App\Models\Account;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class EntryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $sortBy = 'Data';
        $sort = 'date';
        $orderBy = 'desc';
        $perPage = 20;
        $q = null;

        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');
        if ($request->has('q')) $q = $request->query('q');

        if ($sortBy == "Valor") $sort = 'value';
        if ($sortBy == "Data") $sort = 'date';
        if ($sortBy == "Conta") $sort = 'account_id';

        $entries = Entry::search($q)->orderBy($sort, $orderBy)->paginate($perPage);
        $accounts = Account::where('type', 'A')->get();
        $companies = Company::all();

        return view('dre.entries.index', compact('entries', 'accounts', 'companies', 'orderBy', 'sortBy', 'q', 'perPage'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'value',
            'es',
            'account',
            'company',
            'info'
        ]);

        //echo '<pre>';
        //var_dump($request);
        //exit();

        $messages = [
            'account.required' => 'Selecionar Conta',
            'value.required' => 'Informar Valor do Lançamento',
            'date.required' => 'Informar Data de Lançamento',
        ];

        $validator = Validator::make(
            $data,
            [
                'account' => ['required'],
                'value' => ['required', 'numeric'],
            ],
            $messages
        );

        if ($validator->fails()) {
            //           return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
            return redirect()->route('entries.index')
                ->withErrors($validator)
                ->withInput();
        } else {
            $entry = new Entry;
            $entry->value = $data['value'];
            $entry->es = $data['es'];
            $entry->account_id = $data['account'];
            $entry->company_id = $data['company'];
            $entry->info = $data['info'];
            $entry->date = '2020-11-25';
            $entry->save();
            return redirect()->route('entries.index');
        }
    }  // store
    
    
    
    // tentativa de funcao p/retornar um json para o ajax
    public function addEntry(Request $request)
    {
        $data = $request->only([
            'value',
            'date',
            'es',
            'account',
            'company',
            'info'
            ]);
            
            //echo '<pre>';
            //var_dump($request);
            //exit();
            
            $messages = [
                'account.required' => 'Selecionar Conta',
                'value.required' => 'Informar Valor do Lançamento',
                'date.required' => 'Informar Data de Lançamento',
                'date.date' => 'Data Inválida',
            ];
            
            $validator = Validator::make(
                $data,
                [
                    'date' => ['date', 'required'],
                    'account' => ['required'],
                    'value' => ['required', 'numeric'],
                ],
                $messages
            );
            
            if ($validator->fails())
            {
                return response()->json(['errors'=>$validator->errors()->all()]);
             // return Response::json(array('errors'=> $validator->getMessageBag()->toarray()));
            }
            
            $entry = new Entry;
            $entry->value = $data['value'];
            $entry->es = $data['es'];
            $entry->account_id = $data['account'];
            $entry->company_id = $data['company'];
            $entry->info = $data['info'];
            $entry->date =  $data['date'];
            $entry->save();
            $entry->account_description = $entry->account->description;
            $entry->company_name = $entry->company->name;
            return response()->json($entry);
            //return response()->json(['success'=>'Incluído com Sucesso']);

    } //addEntry







    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function updateEntry(Request $request)
    {
        $data = $request->only([
            'id',
            'value',
            'date',
            'es',
            'account',
            'company',
            'info'
            ]);


            $messages = [
                'id.exists' => 'Lançamento inválido, tente novamente',
                'account.required' => 'Selecionar Conta',
                'value.required' => 'Informar Valor do Lançamento',
                'date.required' => 'Informar Data de Lançamento',
                'date.date' => 'Data Inválida',
            ];
            

            $validator = Validator::make(
                $data,
                [
                    'id' => ['exists:entries'],
                    'date' => ['date', 'required'],
                    'account' => ['required'],
                    'value' => ['required', 'numeric'],
                ],
                $messages
            );
            
            if ($validator->fails())
            {
                return response()->json(['errors'=>$validator->errors()->all()]);
             // return Response::json(array('errors'=> $validator->getMessageBag()->toarray()));
            }
            

            $entry = Entry::find($data['id']);
            if ($entry) {
                $entry->value = $data['value'];
                $entry->es = $data['es'];
                $entry->account_id = $data['account'];
                $entry->company_id = $data['company'];
                $entry->info = $data['info'];
                $entry->date =  $data['date'];
                $entry->save();
                $entry->account_description = $entry->account->description;
                $entry->company_name = $entry->company->name;
                return response()->json($entry);
               // return redirect()->route('products.index');

            }

    }




    public function destroy($id)
    {
        $entry = Entry::find($id);
        $entry->delete();
        return redirect()->route('entries.index');
    }
}
