
--DROP TABLE plano_conta;
CREATE TABLE plano_conta
( 
   id_plano_conta   varchar(12) PRIMARY KEY   -- dados da conta ex. 1.02.01 
 , descricao        varchar(50)               -- descricao da conta cadastrada ex. Vendas Externas
 , tipo_conta       varchar(1)                -- tipo de conta do cc Analitica ou Sintética, dominio discreto: A ou S, em situação de produção merece uma constraint check
); 
 
--DROP TABLE lancamento;
CREATE TABLE lancamento 
(
   id_lancamento    integer   PRIMARY KEY    -- id do lancamento, recomenda-se auto incremento, mas para simplifcar fica sem auto incremento
 , dt_lancamento    date                     -- data do lancamento 
 , numero_doc       varchar(40)              -- numero do documento a ser informado  
 , id_plano_conta   varchar(12)              -- chave estrangeira para a tabela plano_conta, mas para simplificar apenas iremos convencionar, não será habilitado a FK, recomendo colocar not null
 , tipo_lancamento  varchar(1)               -- tipo de lancamento 'E' = Entrada 'S' = Saida 
 , historico        varchar(100)             -- historico do lancamento 
 , valor_lancamento numeric(15,2)            -- valor informado 
);


-- Receitas 
 
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1','Receita','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.1','Vendas Internas','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.1.1','Escola','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.1.2','Escritório','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.2','Vendas Externas','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.2.1','Livro','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('1.2.2','Brinquedos','A');
 
-- Despesas
 
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2','Despesas','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.1','Fornecedores','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.1.1','Nacional','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.1.2','Importado','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.2','Escritório','S');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.2.1','Materiais de limpeza','A');
INSERT INTO plano_conta (id_plano_conta, descricao, tipo_conta) VALUES ('2.2.2','Materiais de Escritório','A');
 
-- Vamos povoar a tabela lancamento: 
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (1,'2012-07-03','0000084','1.2.2','E',NULL,10.55); 
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (2,'2012-07-03','0000084','1.2.2','S',NULL,2.50); 
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (3,'2012-07-01','0000021','1.1.2','E',NULL,50.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento,numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (4,'2012-07-01','0000042','1.2.2','E',NULL,100.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (5,'2012-07-04','0000084','1.2.2','E',NULL,160.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (6,'2012-07-04','0000084','1.2.2','S',NULL,80.00);
 
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (7,'2012-07-04','0000142','2.2.1','S',NULL,40.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (8,'2012-07-07','0000210','2.2.2','S',NULL,80.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (9,'2012-07-13','0000242','2.2.2','S',NULL,20.00);
INSERT INTO lancamento (id_lancamento, dt_lancamento, numero_doc, id_plano_conta, tipo_lancamento, historico, valor_lancamento) VALUES (10,'2012-07-13','0000284','2.2.1','S',NULL,15.00);








-- RAZAO DETALHADO ---

SELECT
    lan.id_lancamento,
    lan.dt_lancamento,
    lan.numero_doc,
    lan.id_plano_conta,
    lan.tipo_lancamento,
    lan.historico,
    COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento < lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'E'
    ),
    0.00
    ) - COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento < lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'S'
    ),
    0.00
    ) AS saldo_inicial,
    COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento = lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'E'
    ),
    0.00
    ) AS entrada,
    COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento = lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'S'
    ),
    0.00
    ) AS saida,
    COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento <= lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'E'
    ),
    0.00
    ) - COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento <= lan.dt_lancamento AND id_plano_conta = lan.id_plano_conta AND tipo_lancamento = 'S'
    ),
    0.00
    ) AS saldo_final
FROM
    lancamento AS lan
JOIN plano_conta AS plc
ON
    plc.id_plano_conta = lan.id_plano_conta
WHERE
    lan.dt_lancamento >= '2012-07-04' AND lan.dt_lancamento <= '2012-07-14' AND lan.id_plano_conta = '2.2.2'
ORDER BY
    lan.dt_lancamento ASC








-- RAZAO SUMARIZADO
-- razao sumarizado por plano de conta (Script para Firebird 2.5.1, Oracle 11g R2, Postgres 9.1, SQL Server 2012)   
    SELECT x.id_plano_conta,
           pcx.descricao,
    COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento < '2012-07-04' AND id_plano_conta = x.id_plano_conta AND tipo_lancamento = 'E'
    ),
    0.00
    ) - COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento < '2012-07-04' AND id_plano_conta = x.id_plano_conta AND tipo_lancamento = 'S'
    ),
    0.00
    ) AS saldo_inicial

         , sum(x.entrada) AS entrada
         , sum(x.saida)   AS saida

   , COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento <=  '2012-07-14' AND id_plano_conta = x.id_plano_conta AND tipo_lancamento = 'E'
    ),
    0.00
    ) - COALESCE(
        (
        SELECT
            SUM(valor_lancamento)
        FROM
            lancamento
        WHERE
            dt_lancamento <=  '2012-07-14' AND id_plano_conta = x.id_plano_conta AND tipo_lancamento = 'S'
    ),
    0.00
    ) AS saldo_final


      FROM
         ( 
           SELECT lan.id_plano_conta
                , CASE WHEN tipo_lancamento = 'E' THEN 
                            valor_lancamento
                       ELSE 
                            0.00 
                  END AS entrada 
                , CASE WHEN tipo_lancamento = 'S' THEN 
                            valor_lancamento
                       ELSE 
                            0.00 
                  END AS saida 
             FROM lancamento AS lan
             JOIN plano_conta AS plc 
               ON plc.id_plano_conta  = lan.id_plano_conta
            WHERE lan.dt_lancamento >= '2012-07-04'
              AND lan.dt_lancamento <= '2012-07-14'
              AND lan.id_plano_conta  = '2.2.2'
         ) AS x
      JOIN plano_conta AS pcx
        ON pcx.id_plano_conta = x.id_plano_conta 
  GROUP BY x.id_plano_conta
         , pcx.descricao
  ORDER BY x.id_plano_conta ASC 
      ;













-- BALANCETE --
 
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
 
    
    