<?php

namespace App\Http\Controllers\dre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class SettingController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $setting = Setting::first();
        $accounts = Account::all();
        if ($setting) {
            return view('dre.settings.edit', [
                'setting' => $setting,
                'accounts' => $accounts
            ]);
        } else
        {
            return view('dre.settings.create', [
                'accounts' => $accounts
            ]);
        }
        return redirect()->route('admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->only([
            'income_account',
            'expense_account'
        ]);


        $messages = [
            'income_account.required' => 'Informar Conta de Receita Bruta',
            'expense_account.required' => 'Informar Conta Para Total das Despesas',
        ];

        $validator = Validator::make(
            $data,
            [
                'income_account' => [
                    'required', 'string', 'max:100'                    
                ],
                'expense_account' => [
                    'required', 'string', 'max:100'                    
                ],
            ],
            $messages
        );


        if ($validator->fails()) {
            return redirect()->route('settings.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $setting = new Setting;
        $setting->income_account = $data['income_account'];
        $setting->expense_account = $data['expense_account'];
        $setting->save();

        return redirect()->route('admin');
        
    } // fim do store

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
        $setting = Setting::find($id);
        if ($setting) {
            $data = $request->only([
                'income_account',
                'expense_account'
            ]);

            $messages = [
                'income_account.required' => 'Informar Conta Receita Bruta',
                'expense_account.required' => 'Informar Conta para Total das Despesas',
            ];
            // so fazer este validator quando alterar o nome
            $validator = Validator::make($data, [
                'income_account' => ['required', 'string', 'max:255', 
                ],
                'expense_account' => ['required', 'string', 'max:255', 
                ],
            ],
            $messages
            );
            if ($validator->fails()) {
                return redirect()->route('settings.edit', [
                    'setting' => $id
                ])->withErrors($validator);
            }
            $setting->income_account = $data['income_account'];
            $setting->expense_account = $data['expense_account'];
            $setting->save();
        } // if($company)

        return redirect()->route('admin');

    }// update


    public function destroy($id)
    {
        //
    }
}
