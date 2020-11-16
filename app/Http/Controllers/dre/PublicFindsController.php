<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ncm;
use App\Models\Uf;
use App\Models\Municipio;

class PublicFindsController extends Controller
{
    public function ncms()
    {
        $ncms = Ncm::all();
        echo $ncms;
    }
    //

    public function municipios(Request $request)
    {
        $data = $request->only([
            'uf'
        ]);
        $uf = Uf::where('nome', $data['uf'])->first();
        if ($uf) {
            $municipios = Municipio::where('uf', $uf->uf)->get();
            echo $municipios;
        } else
            echo '';
    }
}
