-- Estruturas para IA, ML e Experiência do Cliente

-- Previsão de Demanda
CREATE TABLE previsoes_demanda (
    id_previsao INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    id_depositante INT NOT NULL,
    data_previsao DATE NOT NULL,
    quantidade_prevista DECIMAL(15,3),
    intervalo_confianca DECIMAL(5,2),
    sazonalidade_factor DECIMAL(5,2),
    tendencia_factor DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Histórico de Acurácia
CREATE TABLE historico_previsoes (
    id_historico INT PRIMARY KEY AUTO_INCREMENT,
    id_previsao INT NOT NULL,
    quantidade_real DECIMAL(15,3),
    erro_percentual DECIMAL(5,2),
    data_medicao DATE NOT NULL,
    FOREIGN KEY (id_previsao) REFERENCES previsoes_demanda(id_previsao)
);

-- Otimização de Layout
CREATE TABLE sugestoes_layout (
    id_sugestao INT PRIMARY KEY AUTO_INCREMENT,
    id_area INT NOT NULL,
    tipo_sugestao ENUM('REALOCACAO', 'CONSOLIDACAO', 'EXPANSAO') NOT NULL,
    descricao TEXT,
    impacto_estimado DECIMAL(5,2), -- percentual de melhoria
    custo_estimado DECIMAL(10,2),
    prioridade INT,
    status ENUM('PENDENTE', 'APROVADA', 'IMPLEMENTADA', 'REJEITADA') DEFAULT 'PENDENTE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_area) REFERENCES areas_armazem(id_area)
);

-- Heat Maps
CREATE TABLE heatmap_movimentacao (
    id_registro INT PRIMARY KEY AUTO_INCREMENT,
    id_endereco INT NOT NULL,
    data_registro DATE NOT NULL,
    quantidade_movimentacoes INT,
    tempo_medio_picking DECIMAL(10,2),
    frequencia_acesso INT,
    peso_relevancia DECIMAL(5,2),
    FOREIGN KEY (id_endereco) REFERENCES enderecos(id_endereco)
);

-- Manutenção Preditiva
CREATE TABLE predicoes_manutencao (
    id_predicao INT PRIMARY KEY AUTO_INCREMENT,
    tipo_equipamento VARCHAR(50),
    codigo_equipamento VARCHAR(50),
    probabilidade_falha DECIMAL(5,2),
    tempo_estimado_falha INT, -- em dias
    impacto_estimado DECIMAL(10,2),
    sugestao_manutencao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Portal do Cliente
CREATE TABLE portal_acessos (
    id_acesso INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    usuario VARCHAR(100) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    ultimo_acesso TIMESTAMP,
    status ENUM('ATIVO', 'BLOQUEADO', 'EXPIRADO') DEFAULT 'ATIVO',
    token_refresh VARCHAR(255),
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Preferências do Cliente
CREATE TABLE preferencias_cliente (
    id_preferencia INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    tipo_relatorio VARCHAR(50),
    frequencia_envio ENUM('DIARIO', 'SEMANAL', 'MENSAL') DEFAULT 'DIARIO',
    formato_arquivo ENUM('PDF', 'EXCEL', 'CSV', 'JSON') DEFAULT 'PDF',
    horario_envio TIME,
    emails_destino TEXT,
    alertas_enabled BOOLEAN DEFAULT true,
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Chatbot
CREATE TABLE chatbot_conversas (
    id_conversa INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    inicio_conversa TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fim_conversa TIMESTAMP,
    satisfacao_cliente INT, -- 1 a 5
    resolvido BOOLEAN DEFAULT false,
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Mensagens do Chatbot
CREATE TABLE chatbot_mensagens (
    id_mensagem INT PRIMARY KEY AUTO_INCREMENT,
    id_conversa INT NOT NULL,
    tipo ENUM('CLIENTE', 'BOT', 'HUMANO') NOT NULL,
    mensagem TEXT NOT NULL,
    intencao_detectada VARCHAR(100),
    confianca_resposta DECIMAL(5,2),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conversa) REFERENCES chatbot_conversas(id_conversa)
);

-- APIs Personalizadas
CREATE TABLE api_keys (
    id_key INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    chave_api VARCHAR(255) UNIQUE NOT NULL,
    descricao VARCHAR(100),
    limite_requisicoes INT DEFAULT 1000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    status ENUM('ATIVO', 'SUSPENSO', 'EXPIRADO') DEFAULT 'ATIVO',
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Logs de API
CREATE TABLE api_logs (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    id_key INT NOT NULL,
    endpoint VARCHAR(255),
    metodo VARCHAR(10),
    status_code INT,
    tempo_resposta DECIMAL(10,2),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_key) REFERENCES api_keys(id_key)
);

-- SLAs
CREATE TABLE slas_cliente (
    id_sla INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    tipo_operacao VARCHAR(50),
    tempo_maximo INT, -- em minutos
    prioridade INT DEFAULT 1,
    penalidade_percentual DECIMAL(5,2),
    horario_inicio TIME,
    horario_fim TIME,
    dias_semana VARCHAR(20), -- ex: "1,2,3,4,5"
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Procedures

DELIMITER //

-- Procedure para Previsão de Demanda
CREATE PROCEDURE sp_calcular_previsao_demanda(
    IN p_id_produto INT,
    IN p_id_depositante INT,
    IN p_data_inicio DATE,
    IN p_data_fim DATE
)
BEGIN
    DECLARE v_quantidade_media DECIMAL(15,3);
    DECLARE v_sazonalidade DECIMAL(5,2);
    DECLARE v_tendencia DECIMAL(5,2);
    
    -- Calcula média histórica
    SELECT AVG(quantidade)
    INTO v_quantidade_media
    FROM movimentacoes_lote ml
    JOIN lotes l ON ml.id_lote = l.id_lote
    WHERE l.id_produto = p_id_produto
    AND l.id_depositante = p_id_depositante
    AND DATE(ml.data_movimento) BETWEEN p_data_inicio AND p_data_fim;
    
    -- Calcula sazonalidade (simplificado)
    SET v_sazonalidade = 1.0;
    
    -- Calcula tendência (simplificado)
    SET v_tendencia = 1.0;
    
    -- Insere previsão
    INSERT INTO previsoes_demanda (
        id_produto,
        id_depositante,
        data_previsao,
        quantidade_prevista,
        intervalo_confianca,
        sazonalidade_factor,
        tendencia_factor
    ) VALUES (
        p_id_produto,
        p_id_depositante,
        DATE_ADD(p_data_fim, INTERVAL 1 DAY),
        v_quantidade_media * v_sazonalidade * v_tendencia,
        0.95,
        v_sazonalidade,
        v_tendencia
    );
END //

-- Procedure para Otimização de Layout
CREATE PROCEDURE sp_otimizar_layout(
    IN p_id_area INT
)
BEGIN
    DECLARE v_total_movimentacoes INT;
    DECLARE v_media_tempo DECIMAL(10,2);
    
    -- Calcula métricas
    SELECT 
        COUNT(*),
        AVG(tempo_medio_picking)
    INTO 
        v_total_movimentacoes,
        v_media_tempo
    FROM heatmap_movimentacao
    WHERE id_endereco IN (
        SELECT id_endereco
        FROM enderecos
        WHERE id_area = p_id_area
    )
    AND data_registro >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY);
    
    -- Gera sugestão
    INSERT INTO sugestoes_layout (
        id_area,
        tipo_sugestao,
        descricao,
        impacto_estimado,
        prioridade
    )
    SELECT 
        p_id_area,
        CASE 
            WHEN v_media_tempo > 120 THEN 'REALOCACAO'
            WHEN v_total_movimentacoes < 100 THEN 'CONSOLIDACAO'
            ELSE 'EXPANSAO'
        END,
        CONCAT('Sugestão baseada em ', v_total_movimentacoes, ' movimentações'),
        CASE 
            WHEN v_media_tempo > 120 THEN 15.0
            WHEN v_total_movimentacoes < 100 THEN 10.0
            ELSE 5.0
        END,
        CASE 
            WHEN v_media_tempo > 120 THEN 1
            WHEN v_total_movimentacoes < 100 THEN 2
            ELSE 3
        END;
END //

-- Procedure para Atendimento Chatbot
CREATE PROCEDURE sp_processar_mensagem_chatbot(
    IN p_id_conversa INT,
    IN p_mensagem TEXT
)
BEGIN
    DECLARE v_intencao VARCHAR(100);
    DECLARE v_confianca DECIMAL(5,2);
    DECLARE v_resposta TEXT;
    
    -- Simula processamento de linguagem natural
    SET v_intencao = 'consulta_status';
    SET v_confianca = 0.95;
    SET v_resposta = 'Entendi que você quer consultar o status. Como posso ajudar?';
    
    -- Registra mensagem do cliente
    INSERT INTO chatbot_mensagens (
        id_conversa,
        tipo,
        mensagem,
        intencao_detectada,
        confianca_resposta
    ) VALUES (
        p_id_conversa,
        'CLIENTE',
        p_mensagem,
        v_intencao,
        v_confianca
    );
    
    -- Registra resposta do bot
    INSERT INTO chatbot_mensagens (
        id_conversa,
        tipo,
        mensagem,
        intencao_detectada,
        confianca_resposta
    ) VALUES (
        p_id_conversa,
        'BOT',
        v_resposta,
        v_intencao,
        v_confianca
    );
    
    -- Se confiança baixa, encaminha para humano
    IF v_confianca < 0.7 THEN
        UPDATE chatbot_conversas
        SET resolvido = false
        WHERE id_conversa = p_id_conversa;
    END IF;
END //

DELIMITER ;

-- Índices

CREATE INDEX idx_previsoes_data ON previsoes_demanda(data_previsao);
CREATE INDEX idx_heatmap_data ON heatmap_movimentacao(data_registro);
CREATE INDEX idx_predicoes_prob ON predicoes_manutencao(probabilidade_falha);
CREATE INDEX idx_chatbot_timestamp ON chatbot_mensagens(timestamp);
CREATE INDEX idx_api_logs_status ON api_logs(status_code);
