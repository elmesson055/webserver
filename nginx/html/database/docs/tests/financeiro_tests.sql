-- Testes para o Módulo Financeiro

-- Configuração inicial
DELIMITER //

-- Procedure para executar todos os testes
CREATE PROCEDURE sp_run_financeiro_tests()
BEGIN
    -- Variáveis para resultados
    DECLARE v_test_name VARCHAR(100);
    DECLARE v_result VARCHAR(10);
    DECLARE v_message VARCHAR(255);
    
    -- Tabela temporária para resultados
    CREATE TEMPORARY TABLE IF NOT EXISTS test_results (
        test_name VARCHAR(100),
        result VARCHAR(10),
        message VARCHAR(255),
        execution_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- 1. Teste de Análise de Crédito - Score Alto
    SET v_test_name = 'test_analise_credito_score_alto';
    BEGIN
        DECLARE v_resultado VARCHAR(20);
        DECLARE v_mensagem VARCHAR(255);
        
        -- Preparar dados
        UPDATE fornecedores 
        SET dias_atraso = 0,
            valor_em_aberto = 1000,
            limite_credito = 10000
        WHERE id = 1;
        
        -- Executar teste
        CALL sp_analise_credito(1, 2000, v_resultado, v_mensagem);
        
        -- Verificar resultado
        IF v_resultado = 'APROVADO' THEN
            SET v_result = 'PASS';
            SET v_message = 'Análise aprovada corretamente';
        ELSE
            SET v_result = 'FAIL';
            SET v_message = CONCAT('Esperado APROVADO, recebido: ', v_resultado);
        END IF;
        
        INSERT INTO test_results VALUES (v_test_name, v_result, v_message, CURRENT_TIMESTAMP);
    END;
    
    -- 2. Teste de Análise de Crédito - Score Baixo
    SET v_test_name = 'test_analise_credito_score_baixo';
    BEGIN
        DECLARE v_resultado VARCHAR(20);
        DECLARE v_mensagem VARCHAR(255);
        
        -- Preparar dados
        UPDATE fornecedores 
        SET dias_atraso = 91,
            valor_em_aberto = 8000,
            limite_credito = 10000
        WHERE id = 1;
        
        -- Executar teste
        CALL sp_analise_credito(1, 2000, v_resultado, v_mensagem);
        
        -- Verificar resultado
        IF v_resultado = 'REPROVADO' THEN
            SET v_result = 'PASS';
            SET v_message = 'Análise reprovada corretamente';
        ELSE
            SET v_result = 'FAIL';
            SET v_message = CONCAT('Esperado REPROVADO, recebido: ', v_resultado);
        END IF;
        
        INSERT INTO test_results VALUES (v_test_name, v_result, v_message, CURRENT_TIMESTAMP);
    END;
    
    -- 3. Teste de Limite de Crédito
    SET v_test_name = 'test_limite_credito';
    BEGIN
        DECLARE v_error BOOLEAN DEFAULT FALSE;
        DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET v_error = TRUE;
        
        -- Preparar dados
        UPDATE fornecedores 
        SET limite_credito = 5000,
            valor_em_aberto = 4000
        WHERE id = 1;
        
        -- Tentar inserir valor acima do limite
        INSERT INTO financeiro_contas_pagar (
            fornecedor_id,
            tipo_documento,
            numero_documento,
            valor_total,
            data_emissao,
            data_vencimento,
            status
        ) VALUES (
            1,
            'NF',
            '12345',
            2000,
            CURRENT_DATE,
            DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY),
            'PENDENTE'
        );
        
        -- Verificar resultado
        IF v_error THEN
            SET v_result = 'PASS';
            SET v_message = 'Limite de crédito bloqueou corretamente';
        ELSE
            SET v_result = 'FAIL';
            SET v_message = 'Permitiu ultrapassar limite de crédito';
        END IF;
        
        INSERT INTO test_results VALUES (v_test_name, v_result, v_message, CURRENT_TIMESTAMP);
    END;
    
    -- 4. Teste de Fechamento Mensal
    SET v_test_name = 'test_fechamento_mensal';
    BEGIN
        DECLARE v_error BOOLEAN DEFAULT FALSE;
        DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET v_error = TRUE;
        
        -- Executar fechamento
        CALL sp_fechamento_mensal(YEAR(CURRENT_DATE), MONTH(CURRENT_DATE), 1);
        
        -- Tentar fazer movimentação retroativa
        INSERT INTO financeiro_movimentacoes (
            conta_id,
            tipo,
            valor,
            data_movimento,
            descricao
        ) VALUES (
            1,
            'DEBITO',
            100,
            DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY),
            'Movimento retroativo'
        );
        
        -- Verificar resultado
        IF v_error THEN
            SET v_result = 'PASS';
            SET v_message = 'Bloqueio de movimentação retroativa funcionou';
        ELSE
            SET v_result = 'FAIL';
            SET v_message = 'Permitiu movimentação retroativa após fechamento';
        END IF;
        
        INSERT INTO test_results VALUES (v_test_name, v_result, v_message, CURRENT_TIMESTAMP);
    END;
    
    -- Exibir resultados
    SELECT * FROM test_results ORDER BY execution_time DESC;
    DROP TEMPORARY TABLE IF EXISTS test_results;
END //

DELIMITER ;

-- Executar todos os testes
CALL sp_run_financeiro_tests();
