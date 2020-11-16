<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ability;
use Illuminate\Support\Facades\Validator;

class AbilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        $abilities = Ability::paginate(5);
        return view('admin.abilities.index', [
            'abilities' => $abilities
        ]);
    }

    public function create()
    {
        return view('admin.abilities.create');
    }

    public function store(Request $request)
    {
        $data = $request->input();

        // valida os campos e retorna caso tenha erro
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return redirect()->route('abilities.create')
                ->withErrors($validator)
                ->withInput();
        }

        //cadastra o grupo de usuario
        $ability = new Ability;
        $ability->name = $data['name'];
        $ability->save();
        return redirect()->route('abilities.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $ability = Ability::find($id);
        if ($ability) {
            return view('admin.abilities.edit', [
                'ability' => $ability
            ]);
        }
        return redirect()->route('abilities.index');
    }

    public function update(Request $request, $id)
    {

        $ability = Ability::find($id);
        if ($ability) {
            $data = $request->input();

            // 1. validações basicas
            $validator = Validator::make([
                'name' => $data['name']
            ], [
                'name' => ['required', 'string', 'max:255']
            ]);

            if ($validator->fails()) {
                return redirect()->route('abilities.edit', [
                    'ability' => $id
                ])->withErrors($validator);
            }

            // 1 alteracao do nome
            $ability->name = $data['name'];

            $ability->save();

        } // if($ability)

        return redirect()->route('abilities.index');

    }//update

    public function destroy($id)
    {
        $ability = Ability::find($id);
        $ability->delete();
        return redirect()->route('abilities.index');
    }

} // fim de tudo
