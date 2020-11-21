 
-- balancete contabil (Script para Firebird 2.5.1, Oracle 11g R2, Postgres 9.1)   
  SELECT pcx.id_plano_conta
       , pcx.descricao 
       , sum(xx.saldo_inicial) AS saldo_inicial 
       , sum(xx.entrada)       AS entrada
       , sum(xx.saida)         AS saida 
       , sum(xx.saldo_final)   AS saldo_final 
    FROM
       (
        SELECT x.id_plano_conta,
             coalesce(
               (
                SELECT sum(valor_lancamento)
                   FROM lancamento
                   WHERE tipo_lancamento = 'E' AND dt_lancamento < '2012-07-04' AND 
                         id_plano_conta  = x.id_plano_conta 
                   ),
                 0.00
				) - COALESCE (
               (
                SELECT sum(valor_lancamento)
                   FROM lancamento
                   WHERE tipo_lancamento = 'S' AND dt_lancamento < '2012-07-04' AND 
                         id_plano_conta  = x.id_plano_conta 
                   ),
                 0.00
				)                       
                   AS saldo_inicial, 
   
           
                
              sum(x.entrada) AS entrada,
              sum(x.saida)   AS saida,
           

           
             coalesce(
               (
                SELECT sum(valor_lancamento)
                   FROM lancamento
                   WHERE tipo_lancamento = 'E' AND dt_lancamento  <=  '2012-07-14' AND 
                         id_plano_conta  = x.id_plano_conta 
                   ),
                 0.00
				) - COALESCE (
               (
                SELECT sum(valor_lancamento)
                   FROM lancamento
                   WHERE tipo_lancamento = 'S' AND dt_lancamento <=  '2012-07-14' AND 
                         id_plano_conta  = x.id_plano_conta 
                   ),
                 0.00
				)                        
                   AS  saldo_final
              
         FROM
             ( 
               SELECT lan.id_plano_conta
                    , CASE WHEN lan.tipo_lancamento = 'E' THEN 
                                lan.valor_lancamento
                           ELSE 
                                0.00 
                      END AS entrada 
                    , CASE WHEN lan.tipo_lancamento = 'S' THEN 
                                lan.valor_lancamento
                           ELSE 
                                0.00 
                      END AS saida   
                 FROM lancamento AS lan
                WHERE lan.dt_lancamento >= '2012-07-04'
                  AND lan.dt_lancamento <= '2012-07-14'

             ) AS x
      GROUP BY x.id_plano_conta
       ) AS xx
         
    JOIN plano_conta pcx 
      ON xx.id_plano_conta LIKE CONCAT (pcx.id_plano_conta, '%')
GROUP BY pcx.id_plano_conta 
       , pcx.descricao 
ORDER BY pcx.id_plano_conta ASC
       ; 
 
    
    