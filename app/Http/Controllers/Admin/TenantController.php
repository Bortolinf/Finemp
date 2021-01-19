<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;

class TenantController extends Controller
{

    public function index(Request $request) {
        return view('admin.registertenant');
    }



    public function register(Request $request) {
        $data = $request->only([
            'name',
            'email',
          //  'prefix',
            'username',
            'useremail',
            'password',
            'password_confirmation'
        ]);

        // valida os campos e retorna caso tenha erro
        $validator= $this->validator($data);
        if($validator->fails()) {
            return redirect()->route('tenant.save')
            ->withErrors($validator)
            ->withInput();
        }

        $tenant = $this->createTenant($data);
        
        $data['tenant_id'] = $tenant->id;
        $user = $this->create($data);
        Auth::login($user);
        return redirect()->route('admin');

    }


    // validadores
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:tenants,email'],
         //   'prefix' => ['required', 'string', 'max:30', 'unique:tenants,prefix'],
            'username' => ['required', 'string', 'max:100'],
            'useremail' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
    }


    // salva dados do Tenant
    protected function createTenant(array $data)
    {
        $ten = new Tenant;
        $ten->name = $data['name'];
        $ten->email = $data['email']; 
        $ten->prefix = $data['name'];
     //   $ten->prefix = $data['prefix'];
        $ten->save();
        return $ten;
       // return Tenant::create([
       //    'name' => $data['name'],
       //     'email' => $data['email'],
       //     'prefix' => $data['prefix'],
       // ]);
    }


    // salva o usuario
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['username'],
            'email' => $data['useremail'],
            'admin' => 1,
            'tenant_id' => $data['tenant_id'],
            'password' => Hash::make($data['password']),
        ]);
    }





}
