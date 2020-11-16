<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ncm;

class PublicReadingsController extends Controller
{
    public function ncm($ncm) {
        $ncm = Ncm::where('codncm', $ncm)->first();
        if($ncm) {
            echo $ncm;
        } else {
            echo '{"erro":"NCM não Cadastrada"}';
        }
    }
    
}
