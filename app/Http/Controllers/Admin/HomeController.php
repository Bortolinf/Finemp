<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Entry;
use App\Models\Account;
use App\Models\Company;
use App\Models\Setting;
use Carbon\Carbon;


class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $user = User::find(Auth::id());
        $loggedTenant = $user->tenant_id;

        $data1 = [];
        $data1['labels'] = [];
        $data1['values'] = [];

        $data2 = [];
        $data2['labels'] = [];
        $data2['incomes'] = [];
        $data2['expenses'] = [];


        // montagem dos dados para o dashboard
        if ($user->can('DashBoard')) {

            //conta de receitas e despesas
            $setting = Setting::First();
            $income_account = '1.1.1.1';
            $expense_account = '2.1.1';
            if ($setting) {
                $income_account = $setting->income_account;
                $expense_account = $setting->expense_account;
            }
            
   
            $receitas = DB::table('entries')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->where('account_id', 'like', $income_account.'%')
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->orderBy('date', 'desc')
                ->select('account_id', 'date', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('date', 'account_id')
                ->limit(50)
                ->get();
            // var_dump($receitas);

            $graf1 = [];

            foreach($receitas as $receita) { 
                // aqui limita a quantidade de datas p/aparecer no grafico
                if(count($graf1) > 4){
                    break;
                }
                $date = strtotime($receita->date);
                $graf1[$receita->date]['data'] = date('d-m-Y', $date);
                if (!isset($graf1[$receita->date]['entradas'])){
                    $graf1[$receita->date]['entradas'] = 0;
                }
                if (!isset($graf1[$receita->date]['saidas'])){
                    $graf1[$receita->date]['saidas'] = 0;
                }

                if ($receita->type == 'R') {
                    $graf1[$receita->date]['entradas'] += $receita->total_vl;
                } 
                if ($receita->type == 'D') {
                    $graf1[$receita->date]['saidas'] += $receita->total_vl;
                } 

            }

            //var_dump($graf1);
            //faz esta inversao para exibir no grafico em ordem crescente
            $grafr = array_reverse($graf1);
            foreach($grafr as $graf) {
                $data1['labels'][] = $graf['data'];
                $valor = $graf['entradas'] - $graf['saidas'];
                $data1['values'][] = $valor;
            }


            // dados para o grafico 2 (ultimos 4 meses - receitas x despesas)
            $date = Carbon::now();
            $mes4 = $date->format('m');
            $ano4 = $date->format('Y');
            // per 3
            $date = $date->subMonth();
            $mes3 = $date->format('m');
            $ano3 = $date->format('Y');
            // per 2
            $date = $date->subMonth();
            $mes2 = $date->format('m');
            $ano2 = $date->format('Y');
            // per 1
            $date = $date->subMonth();
            $mes1 = $date->format('m');
            $ano1 = $date->format('Y');

            $data2['labels'][] = $mes1.'/'.$ano1;
            $data2['labels'][] = $mes2.'/'.$ano2;
            $data2['labels'][] = $mes3.'/'.$ano3;
            $data2['labels'][] = $mes4.'/'.$ano4;

            
            // gera uma relacao das receitas e despesas do periodo 1 
            $receitas1 = DB::table('entries')
                ->where('account_id', 'like', $income_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano1)
                ->whereMonth('date', '=', $mes1)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            $despesas1 = DB::table('entries')
                ->where('account_id', 'like', $expense_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano1)
                ->whereMonth('date', '=', $mes1)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            // gera uma relacao das receitas e despesas do periodo 2 
            $receitas2 = DB::table('entries')
                ->where('account_id', 'like', $income_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano2)
                ->whereMonth('date', '=', $mes2)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            $despesas2 = DB::table('entries')
                ->where('account_id', 'like', $expense_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano2)
                ->whereMonth('date', '=', $mes2)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();


            // gera uma relacao das receitas e despesas do periodo 3 
            $receitas3 = DB::table('entries')
                ->where('account_id', 'like', $income_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano3)
                ->whereMonth('date', '=', $mes3)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            $despesas3 = DB::table('entries')
                ->where('account_id', 'like', $expense_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano3)
                ->whereMonth('date', '=', $mes3)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();


            // gera uma relacao das receitas e despesas do periodo 4 
            $receitas4 = DB::table('entries')
                ->where('account_id', 'like', $income_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano4)
                ->whereMonth('date', '=', $mes4)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            $despesas4 = DB::table('entries')
                ->where('account_id', 'like', $expense_account.'%')
                ->where('entries.tenant_id', '=', $loggedTenant)
                ->whereYear('date', '=', $ano4)
                ->whereMonth('date', '=', $mes4)
                ->join('accounts', function($join)
                {
                    $join->on('entries.account_id', '=', 'accounts.id_account')
                         ->on('entries.tenant_id', '=', 'accounts.tenant_id');
                })
                ->select('account_id', DB::raw('SUM(value) as total_vl'), 'accounts.type')
                ->groupBy('account_id')
                ->get();

            // calcula o somatorio dos dados p/gerar o elemento do grafico
            $data2['incomes'][0] = 0;
            foreach($receitas1 as $receita) { 
                if ($receita->type == 'R') {
                    $data2['incomes'][0] += $receita->total_vl;
                } 
                if ($receita->type == 'D') {
                    $data2['incomes'][0] -= $receita->total_vl;
                } 
            }

            $data2['expenses'][0] = 0;
            foreach($despesas1 as $despesa) { 
                if ($despesa->type == 'D') {
                    $data2['expenses'][0] += $despesa->total_vl;
                } 
                if ($despesa->type == 'R') {
                    $data2['expenses'][0] -= $despesa->total_vl;
                } 
            }

            // per 2
            $data2['incomes'][1] = 0;
            foreach($receitas2 as $receita) { 
                if ($receita->type == 'R') {
                    $data2['incomes'][1] += $receita->total_vl;
                } 
                if ($receita->type == 'D') {
                    $data2['incomes'][1] -= $receita->total_vl;
                } 
            }

            $data2['expenses'][1] = 0;
            foreach($despesas2 as $despesa) { 
                if ($despesa->type == 'D') {
                    $data2['expenses'][1] += $despesa->total_vl;
                } 
                if ($despesa->type == 'R') {
                    $data2['expenses'][1] -= $despesa->total_vl;
                } 
            }

            // per 3
            $data2['incomes'][2] = 0;
            foreach($receitas3 as $receita) { 
                if ($receita->type == 'R') {
                    $data2['incomes'][2] += $receita->total_vl;
                } 
                if ($receita->type == 'D') {
                    $data2['incomes'][2] -= $receita->total_vl;
                } 
            }

            $data2['expenses'][2] = 0;
            foreach($despesas3 as $despesa) { 
                if ($despesa->type == 'D') {
                    $data2['expenses'][2] += $despesa->total_vl;
                } 
                if ($despesa->type == 'R') {
                    $data2['expenses'][2] -= $despesa->total_vl;
                } 
            }

            // per 4
            $data2['incomes'][3] = 0;
            foreach($receitas4 as $receita) { 
                if ($receita->type == 'R') {
                    $data2['incomes'][3] += $receita->total_vl;
                } 
                if ($receita->type == 'D') {
                    $data2['incomes'][3] -= $receita->total_vl;
                } 
            }

            $data2['expenses'][3] = 0;
            foreach($despesas4 as $despesa) { 
                if ($despesa->type == 'D') {
                    $data2['expenses'][3] += $despesa->total_vl;
                } 
                if ($despesa->type == 'R') {
                    $data2['expenses'][3] -= $despesa->total_vl;
                } 
            }

           // var_dump($data2);

        }


        return view('admin.home', [
            'data1' => $data1,
            'data2' => $data2
        ]);

    }


}
