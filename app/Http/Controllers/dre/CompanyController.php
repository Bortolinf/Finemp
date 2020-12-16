<?php

namespace App\Http\Controllers\dre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;





class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $companies = Company::paginate(10);
        return view('dre.companies.index', [
            'companies' => $companies
        ]);
    }

    public function create()
    {
        $loggedId = Auth::id();
        $user = User::find($loggedId);

        if ($user->can('Editar Filiais')) {
            return view('dre.companies.create');
        } else {
            return redirect()->route('companies.index');
        }
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name'
        ]);


        $messages = [
            'name.unique' => 'Nome da Filial já Utilizado',
            'name.required' => 'Informar Nome da Filial',
        ];

        $validator = Validator::make(
            $data,
            [
                'name' => [
                    'required', 'string', 'max:100',
                    Rule::unique('companies')->where(function ($query) {
                        return $query->where('tenant_id', Auth::user()->tenant_id);
                    })
                ],
            ],
            $messages
        );


        if ($validator->fails()) {
            return redirect()->route('companies.create')
                ->withErrors($validator)
                ->withInput();
        }

        $company = new Company;
        $company->name = $data['name'];
        $company->save();

        return redirect()->route('companies.index');
    } // fim do store

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $company = Company::find($id);
        if ($company) {
            return view('dre.companies.edit', [
                'company' => $company
            ]);
        }
        return redirect()->route('companies.index');
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        if ($company) {
            $data = $request->only([
                'name'
            ]);


            $messages = [
                'name.unique' => 'Nome da Filial já Utilizado',
                'name.required' => 'Informar Nome da Filial'
            ];
            // so fazer este validator quando alterar o nome
            if( $data['name'] != $company->name ) {       
                $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255', 
                    Rule::unique('companies')->where(function ($query) {
                        return $query->where('tenant_id', Auth::user()->tenant_id);
                    })
                ],
                ],
                $messages
                );
                if ($validator->fails()) {
                    return redirect()->route('companies.edit', [
                        'company' => $id
                    ])->withErrors($validator);
                }
            }

    
            $company->name = $data['name'];
            $company->save();
            
        } // if($company)

        return redirect()->route('companies.index');

    } // update


    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return redirect()->route('companies.index');
    }
}
