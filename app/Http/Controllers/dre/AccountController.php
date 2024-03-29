<?php

namespace App\Http\Controllers\dre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $showInfo = true;
        $accounts = Account::paginate(15);
        if(count($accounts) > 0) {
            $showInfo = false;
        }
        return view('dre.accounts.index', [
            'accounts' => $accounts,
            'showInfo' => $showInfo,
        ]);

    }



    public function autoCreate()
    {
        $loggedId = Auth::id();
        $user = User::find($loggedId);

        $accounts = [];
        $i = 1;
        $accounts[$i]['cod'] = '1';
        $accounts[$i]['dsc'] = 'Resultado';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'R';
        $i++;
        $accounts[$i]['cod'] = '1.1';
        $accounts[$i]['dsc'] = 'LUCRO BRUTO';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'R';
        $i++;
        $accounts[$i]['cod'] = '1.1.1';
        $accounts[$i]['dsc'] = 'Receita Líquida';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'R';
        $i++;
        $accounts[$i]['cod'] = '1.1.1.1';
        $accounts[$i]['dsc'] = 'Receita com Vendas';
        $accounts[$i]['AS'] = 'A';
        $accounts[$i]['RD'] = 'R';
        $i++;
        $accounts[$i]['cod'] = '1.1.1.2';
        $accounts[$i]['dsc'] = '(-)Abatimentos / Impostos';
        $accounts[$i]['AS'] = 'A';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.1.2';
        $accounts[$i]['dsc'] = 'CMV - Custo da Mercadoria Vendida';
        $accounts[$i]['AS'] = 'A';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.2';
        $accounts[$i]['dsc'] = 'DESPESAS';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.2.1';
        $accounts[$i]['dsc'] = 'Despesas Operacionais';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.2.1.1';
        $accounts[$i]['dsc'] = 'Salários e Ordenados';
        $accounts[$i]['AS'] = 'A';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.2.2';
        $accounts[$i]['dsc'] = 'Despesas com Vendas';
        $accounts[$i]['AS'] = 'S';
        $accounts[$i]['RD'] = 'D';
        $i++;
        $accounts[$i]['cod'] = '1.2.2.1';
        $accounts[$i]['dsc'] = 'Comissões';
        $accounts[$i]['AS'] = 'A';
        $accounts[$i]['RD'] = 'D';

        foreach($accounts as $cta){
            $account = new Account;
            $account->id_account = $cta['cod'];
            $account->description = $cta['dsc'];
            $account->summary = $cta['AS'];
            $account->type = $cta['RD'];
            $account->special_rule_value = 0;
            $account->save();
        }

        return redirect()->route('accounts.index');

    }



    public function create()
    {
        $loggedId = Auth::id();
        $user = User::find($loggedId);

        if ($user->can('Editar_Contas')) {
            return view('dre.accounts.create');
        } else {
            return redirect()->route('accounts.index');
        }
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'id_account',
            'description',
            'summary',
            'type'
        ]);


        $messages = [
            'id_account.unique' => 'Código da Conta Já Utilizado',
            'id_account.required' => 'Informar Código da Conta (ex: 1.01)',
            'description.required' => 'Informar Descrição',
        ];

        $validator = Validator::make(
            $data,
            [
                'id_account' => [
                    'required', 'string', 'max:15',
                    Rule::unique('accounts')->where(function ($query) {
                        return $query->where('tenant_id', Auth::user()->tenant_id);
                    })
                ],
                'description' => ['required', 'string', 'max:100'],
            ],
            $messages
        );


        if ($validator->fails()) {
            return redirect()->route('accounts.create')
                ->withErrors($validator)
                ->withInput();
        }

        $account = new Account;
        $account->id_account = $data['id_account'];
        $account->description = $data['description'];
        $account->summary = $data['summary'];
        $account->type = $data['type'];
        $account->special_rule_value = 0;
        $account->save();

        return redirect()->route('accounts.index');
    } // fim do store


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $account = Account::find($id);
        if ($account) {
            return view('dre.accounts.edit', [
                'account' => $account
            ]);
        }
        return redirect()->route('accounts.index');
    }


    public function update(Request $request, $id)
    {
       // $account = Account::select()->where('id_account', $id)->where('tenant_id', Auth::user()->tenant_id)->first();
        $account = Account::find($id);
        if ($account) {
            $data = $request->only([
                'description',
                'summary',
                'type'
            ]);


            $messages = [
                'description.required' => 'Informar Descrição da Conta',
                'summary.requires' => 'Informar Tipo: Sintétca ou Analítica',
                'type.requires' => 'Informar Natureza: Receita ou Despesa'
            ];
            // so fazer este validator quando alterar o nome
            $validator = Validator::make($data, [
                'description' => ['required', 'string', 'max:100'],
                'summary' => ['required', 'string', 'max:1'],
                'type' => ['required', 'string', 'max:1'],
            ],
            $messages
            );

            if ($validator->fails()) {
                return redirect()->route('accounts.edit', [
                    'account' => $id
                ])->withErrors($validator);
            }
    
            $account->description = $data['description'];
            $account->type = $data['type'];
            $account->summary = $data['summary'];
            $account->special_rule_value = 0;
            $account->save();
            
        } // if($account)

        return redirect()->route('accounts.index');

    } // update



    public function destroy($id)
    {
        //$account = Account::find($id);
        $account = Account::select()->where('id_account', $id)->where('tenant_id', Auth::user()->tenant_id)->first();
        if($account){
            $account->delete();
        }
        return redirect()->route('accounts.index');
    }

}
