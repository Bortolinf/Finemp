<?php

namespace App\Http\Controllers\dre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Entry;
use App\Models\Account;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dresimples(Request $request)
    {

        $ten = auth()->user()->tenant_id;
         //  SELECT `date`, `account_id`, `es`, SUM(`value`) FROM `entries` WHERE 1 GROUP by `account_id`
         //$entries = DB::select('select account_id, sum(value), es, date from entries where es = ? group by account_id', ['S']);


        $entries = DB::select("
                        SELECT pcx.id_account
                        , pcx.description 
                        , sum(xx.saldo_inicial) AS saldo_inicial 
                        , sum(xx.entrada)       AS entrada
                        , sum(xx.saida)         AS saida 
                        , sum(xx.saldo_final)   AS saldo_final 
                    FROM
                        (
                        SELECT x.account_id,
                            coalesce(
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'E' AND date < '2020-11-01' AND 
                                        account_id  = x.account_id AND
                                        tenant_id = :ten1
                                    ),
                                0.00
                                ) - COALESCE (
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'S' AND date < '2020-11-01' AND 
                                        account_id  = x.account_id  AND
                                        tenant_id = :ten2
                                    ),
                                0.00
                                )                       
                                    AS saldo_inicial, 
                    
                            
                                
                            sum(x.entrada) AS entrada,
                            sum(x.saida)   AS saida,
                            
                
                            
                            coalesce(
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'E' AND date  <=  '2020-12-01' AND 
                                        account_id  = x.account_id  AND
                                        tenant_id = :ten3
                                    ),
                                0.00
                                ) - COALESCE (
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'S' AND date <=  '2020-12-01' AND 
                                        account_id  = x.account_id  AND
                                        tenant_id = :ten4
                                    ),
                                0.00
                                )                        
                                    AS  saldo_final
                            
                        FROM
                            ( 
                                SELECT lan.account_id
                                    , CASE WHEN lan.es = 'E' THEN 
                                                lan.value
                                            ELSE 
                                                0.00 
                                    END AS entrada 
                                    , CASE WHEN lan.es = 'S' THEN 
                                                lan.value
                                            ELSE 
                                                0.00 
                                    END AS saida   
                                FROM entries AS lan
                                WHERE lan.date >= '2020-11-02'
                                AND lan.date <= '2020-12-14'  and
                                    lan.tenant_id = :ten5
                
                            ) AS x
                    GROUP BY x.account_id
                        ) AS xx
                        
                    JOIN accounts pcx 
                    ON xx.account_id LIKE CONCAT (pcx.id_account, '%')  AND
                       pcx.tenant_id = :ten6
                GROUP BY pcx.id_account
                        , pcx.description 
                ORDER BY pcx.id_account ASC
                        ; 
        ", [
            'ten1' => $ten,
            'ten2' => $ten,
            'ten3' => $ten,
            'ten4' => $ten,
            'ten5' => $ten,
            'ten6' => $ten,
            ] );
            

        // 'SELECT `company_id`,  `date`, `account_id`, `es`, SUM(`value`) as `value` FROM `entries` 
           //                 WHERE es = "S" 
           //                 GROUP by `account_id`, `date`, `es`, `company_id` ');
        
        //var_dump($entries);
        //exit();

        return view('dre.reports.dresimplificado', ['entries' => $entries]);

    } // dresimples


    /////////////////////////////////////////////////////////////////////////////////////

    public function dreDoMes(Request $request)
    {
        $date_i = '2020-11-01';
        $date_f = '2020-12-31';
        if ($request->filled('date_i')) $date_i = $request->input('date_i');
        if ($request->filled('date_f')) $date_f = $request->input('date_f');

       // echo 'i:'.$date_i.'<br>f:'.$date_f;
       // var_dump($request);
       // exit();

        $companies = Company::all();
        $ten = auth()->user()->tenant_id;
        $accounts = Account::all();
        $filterCompanies = [];

        foreach($companies as $company) {
            if ($request->filled('fil'.$company->id)) {
                $filterCompanies[] = $company->id;
            } 
        }


        foreach($accounts as $account) {
            $account->entradas = 0;
            $account->saidas = 0;
            $entries = Entry::where('account_id', 'like', $account->id_account.'%')
                    ->whereBetween('date', [$date_i, $date_f])
                    ->whereIn('company_id', $filterCompanies)
                    ->get();
            foreach($entries as $entry) {
               // var_dump($entry);
               // exit();
                if ($entry->account->type == 'R') {
                    $account->entradas += $entry->value;
                } 
                if ($entry->account->type == 'D') {
                    $account->saidas += $entry->value;
                } 
            }
            $account->saldo_final = $account->entradas - $account->saidas;
        } 

        return view('dre.reports.dreDoMes', ['companies' => $companies, 'filterCompanies' => $filterCompanies, 'accounts' => $accounts, 'date_i' => $date_i, 'date_f' => $date_f ]);


    } //dreDoMes

    ///////////////////////////////////////////////////////////////////////

    public function dreComparativo(Request $request)
    {
        $per_ref = date('Y-m');
        if ($request->filled('per_ref')) $per_ref = $request->input('per_ref');
        
        $date_i = date("Y-m-01", strtotime($per_ref));
        $date_f = date("Y-m-t", strtotime($per_ref));
        $date_i_ant = date("Y-m-01", strtotime("-1 year", strtotime($per_ref)));
        $date_f_ant = date("Y-m-t", strtotime("-1 year", strtotime($per_ref)));
        $date_acm_i = date("Y-01-01", strtotime($per_ref));
        $date_acm_i_ant = date("Y-01-01", strtotime("-1 year", strtotime($per_ref)));

        $cab_date = date("M/y", strtotime($date_i));
        $cab_date_acm = date("M/y", strtotime($date_acm_i)).' a '.date("M/y", strtotime($date_i));

        echo 'cab_date:'.$cab_date.'<br>'; 
        echo 'cab_date_acm:'.$cab_date_acm.'<br>'; 

        echo 'per_ref:'.$per_ref.'<br>'; 

        echo 'i:'.$date_i.'<br>f:'.$date_f.'<br>';
        echo 'i-ant:'.$date_i_ant.'<br>f-ant:'.$date_f_ant.'<br>';
        echo 'acm-i:'.$date_acm_i.'<br>acm-f:'.$date_f.'<br>';
        echo 'acm-i-ant:'.$date_acm_i_ant.'<br>acm-f-ant:'.$date_f_ant.'<br>';
       // var_dump($request);
       // exit();

        $companies = Company::all();
        $ten = auth()->user()->tenant_id;
        $accounts = Account::all();
        $filterCompanies = [];

        foreach($companies as $company) {
            if ($request->filled('fil'.$company->id)) {
                $filterCompanies[] = $company->id;
            } 
        }


        foreach($accounts as $account) {
            $account->entradas = 0;
            $account->saidas = 0;
            $entries = Entry::where('account_id', 'like', $account->id_account.'%')
                    ->whereBetween('date', [$date_i, $date_f])
                    ->whereIn('company_id', $filterCompanies)
                    ->get();
            $entries_acm = Entry::where('account_id', 'like', $account->id_account.'%')
                    ->whereBetween('date', [$date_acm_i, $date_f])
                    ->whereIn('company_id', $filterCompanies)
                    ->get();

            foreach($entries as $entry) {
               // var_dump($entry);
               // exit();
                if ($entry->account->type == 'R') {
                    $account->entradas += $entry->value;
                } 
                if ($entry->account->type == 'D') {
                    $account->saidas += $entry->value;
                } 
            }

            // salva dados do movimento acumulado
            foreach($entries_acm as $entry) {
                // var_dump($entry);
                // exit();
                 if ($entry->account->type == 'R') {
                     $account->entradas_acm += $entry->value;
                 } 
                 if ($entry->account->type == 'D') {
                     $account->saidas_acm += $entry->value;
                 } 
             }
 
            $account->saldo_final = $account->entradas - $account->saidas;
            $account->saldo_final_acm = $account->entradas_acm - $account->saidas_acm;
        } 

        return view('dre.reports.dreComparativo', ['companies' => $companies, 'filterCompanies' => $filterCompanies, 'accounts' => $accounts, 'per_ref' => $per_ref ]);


    } //dreComparativo




}
