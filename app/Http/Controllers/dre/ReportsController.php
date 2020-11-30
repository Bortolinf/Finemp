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
                                        account_id  = x.account_id 
                                    ),
                                0.00
                                ) - COALESCE (
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'S' AND date < '2020-11-01' AND 
                                        account_id  = x.account_id 
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
                                        account_id  = x.account_id 
                                    ),
                                0.00
                                ) - COALESCE (
                                (
                                SELECT sum(value)
                                    FROM entries
                                    WHERE es = 'S' AND date <=  '2020-12-01' AND 
                                        account_id  = x.account_id 
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
                                AND lan.date <= '2020-12-14'
                
                            ) AS x
                    GROUP BY x.account_id
                        ) AS xx
                        
                    JOIN accounts pcx 
                    ON xx.account_id LIKE CONCAT (pcx.id_account, '%')
                GROUP BY pcx.id_account
                        , pcx.description 
                ORDER BY pcx.id_account ASC
                        ; 
        ");
            

        // 'SELECT `company_id`,  `date`, `account_id`, `es`, SUM(`value`) as `value` FROM `entries` 
           //                 WHERE es = "S" 
           //                 GROUP by `account_id`, `date`, `es`, `company_id` ');
        
        //var_dump($entries);
        //exit();

        return view('dre.reports.dresimplificado', ['entries' => $entries]);

    }


}
