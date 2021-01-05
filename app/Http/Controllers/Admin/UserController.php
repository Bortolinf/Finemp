<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $ten = auth()->user()->tenant_id;
        $users = User::where('tenant_id', $ten)->paginate(5);
        $loggedId = Auth::id();
        return view('admin.users.index', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::All();
        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        // valida os campos e retorna caso tenha erro
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:200', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed']
        ]);


        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        //cadastra o usuario
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->admin = 0;
        $user->tenant_id = auth()->user()->tenant_id;
        $user->save();

        //cadastra os perfis de usuario selecionados
        $roles = Role::All();
        foreach ($roles as $role) {
            // tratamento para situacoes com espacos no meio do nome p/fechar com o HTML
            $roleName = str_replace(' ', '_', $role->name);
            if (isset($data[$roleName])) {
                if ($data[$roleName] == 'on') {
                    $user->assignRole($role);
                }
            }
        }

        return redirect()->route('users.index');

    }

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
        $user = User::find($id);
        $roles = Role::All();
        $user_roles = $user->rolesNames();

        foreach ($roles as $role) {
            $role->checked = '';
            if (in_array($role->name, $user_roles)) {
                $role->checked = 'checked';
            }
        }


        if ($user) {
            return view('admin.users.edit', [
                'user' => $user,
                'roles' => $roles
            ]);
        }
        return redirect()->route('users.index');
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

        $user = User::find($id);
        if ($user) {
            $data = $request->input();

            // 1. validações basicas

            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email']
            ], [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:100']
            ]);

            if ($validator->fails()) {
                return redirect()->route('users.edit', [
                    'user' => $id
                ])->withErrors($validator);
            }

            // 1 alteracao do nome
            $user->name = $data['name'];

            // 2 Ver se foi alterado -> ver se ja existe
            if ($user->email != $data['email']) {
                $hasEmail = User::where('email', $data['email'])->get();
                if (count($hasEmail) === 0) {
                    $user->email = $data['email'];
                } else {
                    $validator->errors()->add('email', __('validation.unique', [
                        'attribute' => 'email'
                    ]));
                }
            }

            // 3 senha - ver se digitou, testa tamanho e se batem as senhas
            if (!empty($data['password'])) {
                if (strlen($data['password']) >= 4) {
                    if ($data['password'] === $data['password_confirmation']) {
                        $user->password = Hash::make($data['password']);
                    } else {
                        $validator->errors()->add('password', __('validation.confirmed', [
                            'attribute' => 'password'
                        ]));

                    }
                } else {
                    $validator->errors()->add('password', __('validation.min.string', [
                        'attribute' => 'password',
                        'min' => 4
                    ]));
                }
            }
            
            if (count($validator->errors()) > 0) {
                return redirect()->route('users.edit', [
                    'user' => $id
                ])->withErrors($validator);
            }
    
            $user->save();

            //cadastra os perfis selecionados - e exclui os nao selecionados
            $roles = Role::All();
            foreach ($roles as $role) {
                // tratamento para situacoes com espacos no meio do nome p/fechar com o HTML
                $roleName = str_replace(' ', '_', $role->name);
                if (isset($data[$roleName])) {
                    if ($data[$roleName] == 'on') {
                        $user->assignRole($role);
                    } else {
                        $user->disassignRole($role);
                    }
                } else {
                    $user->disassignRole($role);
                }
            }


            
        } // if($user)
        
        return redirect()->route('users.index');

        
    } //update



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loggedId = Auth::id();
        if($loggedId != $id){
            $user = User::find($id);
            $user->delete();
        }

        return redirect()->route('users.index');
    }
}
