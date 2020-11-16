<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);

        if ($user) {
            return view('admin.profile.index', [
                'user' => $user
            ]);
        }
        return redirect()->route('admin');
    }



    // salvar alteracoes no perfil
    public function save(Request $request)
    {
        $id = intval(Auth::id());
        $user = User::find($id);
        if ($user) {
            $data = $request->only([
                'name',
                'email',
                'password',
                'password_confirmation'
            ]);

            // 1. validações basicas

            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email']
            ], [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:100']
            ]);

            if ($validator->fails()) {
                return redirect()->route('profile', [
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
                return redirect()->route('profile', [
                    'user' => $id
                ])->withErrors($validator);
            }

            $user->save();
            return redirect()->route('profile')
                ->with('warning', 'Usuario alterado com sucesso!');
        } // if($user)
    } // save

}
