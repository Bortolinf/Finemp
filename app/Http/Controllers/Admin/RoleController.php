<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Ability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::paginate(5);
        return view('admin.roles.index', [
            'roles' => $roles
        ]);
    }


    public function create()
    {
        $abilities = Ability::All();
        return view('admin.roles.create', [
            'abilities' => $abilities
        ]);
    }


    public function store(Request $request)
    {

        $data = $request->input();

        // 1. validações basicas
        $messages = [
            'name.unique' => 'Nome do grupo já Utilizado',
            'name.required' => 'Nome deve ser Informado',
        ];    
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 
            Rule::unique('roles')->where(function ($query) {
                return $query->where('tenant_id', Auth::user()->tenant_id);
            })
            ],
        ],
            $messages
        );


        if ($validator->fails()) {
            return redirect()->route('roles.create')
                ->withErrors($validator)
                ->withInput();
        }

        //cadastra o grupo de usuario
        $role = new Role;
        $role->name = $data['name'];
        $role->save();

        //cadastra as permissoes selecionadas
        $abilities = Ability::All();
        foreach ($abilities as $ability) {
            if (isset($data[$ability->name])) {
                if ($data[$ability->name] == 'on') {
                    $role->allowTo($ability);
                }
            }
        }

        return redirect()->route('roles.index');
    } //store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $abilities = Ability::All();
        $role = Role::find($id);
        $role_abilities = $role->abilitiesNames();
        foreach ($abilities as $ability) {
            $ability->checked = '';
            if (in_array($ability->name, $role_abilities)) {
                $ability->checked = 'checked';
            }
        }
        
        if ($role) {
            return view('admin.roles.edit', [
                'role' => $role,
                'abilities' => $abilities
            ]);
        }
        return redirect()->route('roles.index');

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if ($role) {
            $data = $request->input();

            // 1. validações basicas
            $messages = [
                'name.unique' => 'Nome do grupo já Utilizado',
            ];            
            // so fazer este validator quando alterar o nome
            if( $data['name'] != $role->name ) {       
                $validator = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255', 
                    Rule::unique('roles')->where(function ($query) {
                        return $query->where('tenant_id', Auth::user()->tenant_id);
                    })
                ],
                ],
                $messages
                );
                if ($validator->fails()) {
                    return redirect()->route('roles.edit', [
                        'role' => $id
                    ])->withErrors($validator);
                }
            }


            // 1 alteracao do nome
            $role->name = $data['name'];

            $role->save();

            //cadastra as permissoes selecionadas - e excluir as nao selecionadas
            $abilities = Ability::All();
            foreach ($abilities as $ability) {
                if (isset($data[$ability->name])) {
                    if ($data[$ability->name] == 'on') {
                        $role->allowTo($ability);
                    } else {
                        $role->disallowTo($ability);
                    }
                } else {
                    $role->disallowTo($ability);
                }
            }


        } // if($role)

        return redirect()->route('roles.index');
    } //update



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
