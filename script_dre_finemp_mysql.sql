

-- BALANCETE --
 
-- balancete contabil (Script para Firebird 2.5.1, Oracle 11g R2, Postgres 9.1)   
  SELECT pcx.account_id
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
                   WHERE es = 'E' AND date < '2012-07-04' AND 
                         account_id  = x.account_id 
                   ),
                 0.00
				) - COALESCE (
               (
                SELECT sum(value)
                   FROM entries
                   WHERE es = 'S' AND date < '2012-07-04' AND 
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
                   WHERE es = 'E' AND date  <=  '2012-07-14' AND 
                         account_id  = x.account_id 
                   ),
                 0.00
				) - COALESCE (
               (
                SELECT sum(value)
                   FROM entries
                   WHERE es = 'S' AND date <=  '2012-07-14' AND 
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
                WHERE lan.date >= '2012-07-04'
                  AND lan.date <= '2012-07-14'

             ) AS x
      GROUP BY x.account_id
       ) AS xx
         
    JOIN accounts pcx 
      ON xx.account_id LIKE CONCAT (pcx.id_account, '%')
GROUP BY pcx.id_account
       , pcx.description 
ORDER BY pcx.id_account ASC
       ; 
 
    
    